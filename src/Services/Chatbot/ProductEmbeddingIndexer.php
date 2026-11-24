<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Core\Database;
use PDO;

final class ProductEmbeddingIndexer
{
    private PDO $db;

    public function __construct(private readonly GroqClient $client)
    {
        $this->db = Database::connection();
    }

    /**
     * Tìm sản phẩm liên quan theo từ khóa trên bảng products (khi không có vector embedding).
     *
     * @return array<int, array<string, mixed>>
     */
    public function semanticSearch(string $query, int $limit = 12): array
    {
        return $this->keywordSearch($query, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function keywordSearch(string $query, int $limit = 12): array
    {
        $limit = max(1, min(20, $limit));
        $tokens = preg_split('/\s+/u', mb_strtolower(trim($query), 'UTF-8')) ?: [];
        $keywords = array_values(array_filter(
            $tokens,
            static fn (string $w): bool => mb_strlen($w, 'UTF-8') >= 2
        ));

        if ($keywords === []) {
            $clean = mb_strtolower(trim($query), 'UTF-8');
            if ($clean === '') {
                return [];
            }
            $keywords = [$clean];
        }

        $conditions = [];
        $params = [];
        foreach ($keywords as $idx => $kw) {
            $ph = ":kw{$idx}";
            $conditions[] = "(LOWER(p.name) LIKE {$ph} OR LOWER(COALESCE(p.description, '')) LIKE {$ph} OR LOWER(COALESCE(c.name, '')) LIKE {$ph})";
            $params[$ph] = '%' . addcslashes($kw, '%_\\') . '%';
        }

        $whereSql = implode(' OR ', $conditions);
        $sql = "SELECT p.id, p.name, p.slug, p.unit, p.price, p.stock_quantity, p.image_path, p.description
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.is_active = 1 AND COALESCE(c.is_active, 1) = 1 AND p.stock_quantity > 0 AND ({$whereSql})
                ORDER BY p.is_featured DESC, p.rating_avg DESC, p.id DESC
                LIMIT :lim";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $ph => $val) {
            $stmt->bindValue($ph, $val, PDO::PARAM_STR);
        }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}