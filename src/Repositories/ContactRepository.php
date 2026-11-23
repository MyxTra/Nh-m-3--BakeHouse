<?php

declare(strict_types=1);

namespace App\Repositories;

use DateTimeImmutable;

final class ContactRepository extends BaseRepository
{
    public function create(string $fullName, ?string $email, ?string $phone, string $message, ?int $userId = null): void
    {
        $this->execute(
            'INSERT INTO contacts (user_id, full_name, email, phone, message) VALUES (?, ?, ?, ?, ?)',
            [$userId, $fullName, $email, $phone, $message]
        );
    }

    public function countByUser(int $userId): int
    {
        return (int) $this->fetchValue('SELECT COUNT(*) FROM contacts WHERE user_id = ?', [$userId]);
    }

    /** @return array<int, array<string, mixed>> */
    public function listByUser(int $userId, int $limit, int $offset): array
    {
        return $this->fetchAll(
            'SELECT * FROM contacts WHERE user_id = :uid ORDER BY id DESC LIMIT :limit OFFSET :offset',
            ['uid' => $userId, 'limit' => $limit, 'offset' => $offset]
        );
    }

    /** @return array{0: string, 1: array<string, mixed>} */
    private function where(string $search, string $status, string $fromDate = '', string $toDate = ''): array
    {
        $clauses = [];
        $params = [];

        if ($search !== '') {
            $clauses[] = '(full_name LIKE :q OR email LIKE :q2 OR phone LIKE :q3 OR message LIKE :q4)';
            $params['q'] = $params['q2'] = $params['q3'] = $params['q4'] = $this->likeTerm($search);
        }

        if ($status === 'new') {
            $clauses[] = "status <> 'replied'";
        } elseif ($status === 'replied') {
            $clauses[] = "status = 'replied'";
        }

        if ($fromDate !== '') {
            $clauses[] = 'created_at >= :from_dt';
            $params['from_dt'] = $fromDate . ' 00:00:00';
        }

        if ($toDate !== '') {
            $nextDay = DateTimeImmutable::createFromFormat('Y-m-d', $toDate)?->modify('+1 day');
            if ($nextDay !== false) {
                $clauses[] = 'created_at < :to_dt';
                $params['to_dt'] = $nextDay->format('Y-m-d') . ' 00:00:00';
            }
        }

        return [$clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses), $params];
    }

    public function adminCount(string $search, string $status, string $fromDate = '', string $toDate = ''): int
    {
        [$where, $params] = $this->where($search, $status, $fromDate, $toDate);

        return (int) $this->fetchValue("SELECT COUNT(*) FROM contacts {$where}", $params);
    }

    /** @return array<int, array<string, mixed>> */
    public function adminList(string $search, string $status, string $fromDate, string $toDate, int $limit, int $offset): array
    {
        [$where, $params] = $this->where($search, $status, $fromDate, $toDate);
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->fetchAll(
            "SELECT * FROM contacts {$where} ORDER BY id DESC LIMIT :limit OFFSET :offset",
            $params
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT c.*, u.email AS account_email, u.phone AS account_phone
             FROM contacts c LEFT JOIN users u ON u.id = c.user_id WHERE c.id = ?',
            [$id]
        );
    }

    /** @return array{all: int, new: int, replied: int} */
    public function statusCounts(): array
    {
        $row = $this->fetchOne(
            "SELECT COUNT(*) AS total,
                    COALESCE(SUM(status <> 'replied'), 0) AS n_new,
                    COALESCE(SUM(status = 'replied'), 0) AS n_replied
             FROM contacts"
        ) ?? [];

        return [
            'all' => (int) ($row['total'] ?? 0),
            'new' => (int) ($row['n_new'] ?? 0),
            'replied' => (int) ($row['n_replied'] ?? 0),
        ];
    }

    public function setStatus(int $id, string $status): void
    {
        $this->execute('UPDATE contacts SET status = ? WHERE id = ?', [$status, $id]);
    }

    public function reply(int $id, ?string $reply): void
    {
        $this->execute(
            'UPDATE contacts SET admin_reply = ?, admin_replied_at = ?, status = IF(? IS NOT NULL, \'replied\', status) WHERE id = ?',
            [$reply, $reply !== null ? date('Y-m-d H:i:s') : null, $reply, $id]
        );
    }
}