<?php

declare(strict_types=1);

namespace App\Repositories;

final class NotificationRepository extends BaseRepository
{
    public function create(int $userId, string $type, string $title, ?string $message = null, ?string $link = null): void
    {
        $this->execute(
            'INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)',
            [$userId, $type, $title, $message, $link]
        );
    }

    public function unreadCount(int $userId): int
    {
        return (int) $this->fetchValue('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0', [$userId]);
    }

    /** @return array<int, array<string, mixed>> */
    public function recentForUser(int $userId, int $limit): array
    {
        return $this->fetchAll(
            'SELECT * FROM notifications WHERE user_id = :uid ORDER BY id DESC LIMIT :limit',
            ['uid' => $userId, 'limit' => $limit]
        );
    }

    /** @return array<int, array<string, mixed>> */
    public function listForUser(int $userId, int $limit, int $offset): array
    {
        return $this->fetchAll(
            'SELECT * FROM notifications WHERE user_id = :uid ORDER BY id DESC LIMIT :limit OFFSET :offset',
            ['uid' => $userId, 'limit' => $limit, 'offset' => $offset]
        );
    }

    public function countForUser(int $userId): int
    {
        return (int) $this->fetchValue('SELECT COUNT(*) FROM notifications WHERE user_id = ?', [$userId]);
    }

    public function markAllRead(int $userId): void
    {
        $this->execute('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0', [$userId]);
    }
}
