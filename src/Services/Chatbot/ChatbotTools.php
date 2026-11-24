<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Env;
use App\Enums\OrderStatus;
use PDO;

final class ChatbotTools
{
    private PDO $db;

    public function __construct(private readonly ProductEmbeddingIndexer $indexer)
    {
        $this->db = Database::connection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function declarations(): array
    {
        return [
            [
                'name' => 'search_bakery_products',
                'description' => 'Tìm nguyên liệu/dụng cụ còn hàng trong kho BakeHouse theo món bánh hoặc từ khóa.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'query' => [
                            'type' => 'string',
                            'description' => 'Món bánh hoặc nguyên liệu cần tìm',
                        ],
                        'max_price' => [
                            'type' => 'integer',
                            'description' => 'Giá tối đa mỗi sản phẩm (VND), 0 nếu không giới hạn',
                        ],
                    ],
                    'required' => ['query'],
                ],
            ],
            [
                'name' => 'finalize_product_bundle',
                'description' => 'Chốt danh sách thẻ sản phẩm đề xuất kèm số lượng (>= 1).',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'items' => [
                            'type' => 'array',
                            'description' => 'Danh sách sản phẩm và số lượng (mặc định quantity = 1)',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'id' => ['type' => 'integer', 'description' => 'ID sản phẩm'],
                                    'quantity' => ['type' => 'integer', 'description' => 'Số lượng (>= 1)'],
                                ],
                                'required' => ['id', 'quantity'],
                            ],
                        ],
                        'product_ids' => [
                            'type' => 'array',
                            'items' => ['type' => 'integer'],
                            'description' => 'Danh sách ID sản phẩm nếu mỗi món số lượng 1',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'get_shop_policies',
                'description' => 'Lấy thông tin phí ship, hình thức thanh toán, FAQ và liên hệ CSKH BakeHouse.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'topic' => [
                            'type' => 'string',
                            'description' => 'shipping, payment, faq, support hoặc all',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'check_my_orders',
                'description' => 'Tra cứu các đơn hàng gần nhất của người dùng đang đăng nhập.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'limit' => [
                            'type' => 'integer',
                            'description' => 'Số đơn gần nhất (mặc định 3)',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     */
    public function execute(string $name, array $args): array
    {
        return match ($name) {
            'search_bakery_products' => $this->searchProducts(
                (string) ($args['query'] ?? ''),
                (int) ($args['max_price'] ?? 0)
            ),
            'finalize_product_bundle' => $this->buildProductBundle($args),
            'get_shop_policies' => $this->getShopPolicies(),
            'check_my_orders' => $this->checkMyOrders((int) ($args['limit'] ?? 3)),
            default => ['error' => 'Tool không tồn tại: ' . $name],
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function searchProducts(string $query, int $maxPrice): array
    {
        $results = $this->indexer->keywordSearch($query, 12);
        if ($maxPrice > 0) {
            $results = array_values(array_filter(
                $results,
                static fn (array $p): bool => (int) $p['price'] <= $maxPrice
            ));
        }

        return [
            'products' => array_map(static fn (array $p): array => [
                'id' => (int) $p['id'],
                'name' => $p['name'],
                'unit' => $p['unit'],
                'price' => (int) $p['price'],
                'stock_quantity' => (int) $p['stock_quantity'],
            ], $results),
        ];
    }

    /**
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     */
    private function buildProductBundle(array $args): array
    {
        $qtyMap = [];

        if (isset($args['items']) && is_array($args['items'])) {
            foreach ($args['items'] as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $id = (int) ($item['id'] ?? 0);
                $qty = max(1, (int) ($item['quantity'] ?? 1));
                if ($id > 0) {
                    $qtyMap[$id] = ($qtyMap[$id] ?? 0) + $qty;
                }
            }
        }

        if ($qtyMap === [] && isset($args['product_ids']) && is_array($args['product_ids'])) {
            foreach ($args['product_ids'] as $rawId) {
                $id = (int) $rawId;
                if ($id > 0) {
                    $qtyMap[$id] = 1;
                }
            }
        }

        $ids = array_keys($qtyMap);
        if ($ids === []) {
            return ['products' => [], 'total' => 0];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare(
            "SELECT id, name, slug, unit, price, stock_quantity, image_path
             FROM products
             WHERE is_active = 1 AND stock_quantity > 0 AND id IN ({$placeholders})"
        );
        $stmt->execute($ids);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $byId = [];
        foreach ($rows as $r) {
            $byId[(int) $r['id']] = $r;
        }

        $products = [];
        $total = 0;

        foreach ($qtyMap as $id => $requestedQty) {
            if (!isset($byId[$id])) {
                continue;
            }
            $row = $byId[$id];
            $stock = max(1, (int) $row['stock_quantity']);
            $qty = min($requestedQty, $stock);
            $price = (int) $row['price'];
            $lineTotal = $price * $qty;

            $products[] = [
                'id' => $id,
                'name' => $row['name'],
                'slug' => $row['slug'],
                'unit' => $row['unit'],
                'price' => $price,
                'quantity' => $qty,
                'stock_quantity' => $stock,
                'image_path' => $row['image_path'],
                'image_url' => function_exists('img_url') ? img_url($row['image_path'] ?? null) : (string) ($row['image_path'] ?? ''),
            ];
            $total += $lineTotal;
        }

        return ['products' => $products, 'total' => $total];
    }

    /**
     * @return array<string, mixed>
     */
    private function getShopPolicies(): array
    {
        $fee = max(0, Env::int('SHIPPING_FEE', 30000));

        return [
            'shipping_fee_vnd' => $fee,
            'shipping_note' => 'Phí giao hàng cố định là ' . number_format($fee, 0, ',', '.') . '₫/đơn hàng.',
            'payment_methods' => [
                'COD (Thanh toán tiền mặt khi nhận hàng)',
                'Chuyển khoản qua mã VietQR (hiển thị đúng số tiền và mã đơn hàng sau khi đặt)',
            ],
            'faqs' => [
                [
                    'question' => 'Phí vận chuyển tại BakeHouse tính như thế nào?',
                    'answer' => 'Phí giao hàng đồng giá ' . number_format($fee, 0, ',', '.') . '₫/đơn hàng.',
                ],
                [
                    'question' => 'BakeHouse hỗ trợ những hình thức thanh toán nào?',
                    'answer' => 'Hỗ trợ thanh toán COD khi nhận hàng và chuyển khoản VietQR.',
                ],
                [
                    'question' => 'Làm sao để liên hệ hỗ trợ hoặc khiếu nại?',
                    'answer' => 'Bạn có thể gửi tin nhắn qua Form Liên hệ tại /contact để đội ngũ BakeHouse hỗ trợ.',
                ],
            ],
            'contact_page_url' => '/contact',
            'support_note' => 'Khách có thể gửi yêu cầu qua Form Liên hệ tại /contact để shop phản hồi.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function checkMyOrders(int $limit): array
    {
        $userId = Auth::id();
        if ($userId === null) {
            return [
                'logged_in' => false,
                'message' => 'Khách hàng chưa đăng nhập tài khoản nên không thể tra cứu đơn hàng.',
            ];
        }

        $limit = max(1, min(5, $limit));
        $stmt = $this->db->prepare(
            "SELECT order_code, status, payment_method, payment_status, total_amount, created_at
             FROM orders
             WHERE user_id = :uid
             ORDER BY id DESC
             LIMIT {$limit}"
        );
        $stmt->execute(['uid' => (int) $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return [
            'logged_in' => true,
            'orders' => array_map(static function (array $o): array {
                $enum = OrderStatus::tryFrom((string) $o['status']);

                return [
                    'order_code' => $o['order_code'],
                    'status' => $enum !== null ? $enum->label() : $o['status'],
                    'payment_method' => strtoupper((string) $o['payment_method']),
                    'payment_status' => $o['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán',
                    'total_amount' => (int) $o['total_amount'],
                    'created_at' => $o['created_at'],
                ];
            }, $orders),
        ];
    }
}