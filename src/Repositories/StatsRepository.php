<?php

declare(strict_types=1);

namespace App\Repositories;

/** Sales statistics for the admin dashboard and the sales report. */
final class StatsRepository extends BaseRepository
{
    /** All-time summary for the dashboard KPI cards. @return array{revenue: int, orders: int, delivered_orders: int, items_sold: int, average: int} */
    public function summary(): array
    {
        $orders = $this->fetchOne(
            "SELECT COUNT(*) AS total, COALESCE(SUM(status = 'delivered'), 0) AS delivered FROM orders"
        ) ?? ['total' => 0, 'delivered' => 0];
        $sold = $this->fetchOne(
            "SELECT COALESCE(SUM(quantity), 0) AS qty, COALESCE(SUM(unit_price * quantity), 0) AS revenue
             FROM order_items WHERE status = 'delivered'"
        ) ?? ['qty' => 0, 'revenue' => 0];

        $revenue = (int) $sold['revenue'];
        $delivered = (int) $orders['delivered'];

        return [
            'revenue' => $revenue,
            'orders' => (int) $orders['total'],
            'delivered_orders' => $delivered,
            'items_sold' => (int) $sold['qty'],
            'average' => $delivered > 0 ? intdiv($revenue, $delivered) : 0,
        ];
    }

    /** Count products with stock_quantity at or below $threshold. */
    public function lowStockCount(int $threshold = 10): int
    {
        return (int) $this->fetchValue(
            'SELECT COUNT(*) FROM products WHERE stock_quantity <= :threshold',
            ['threshold' => $threshold]
        );
    }

    /** All-time best sellers for the dashboard. @return array<int, array{product_name: string, qty: int, revenue: int}> */
    public function topSelling(int $limit): array
    {
        return $this->fetchAll(
            "SELECT product_name, SUM(quantity) AS qty, SUM(unit_price * quantity) AS revenue
             FROM order_items WHERE status = 'delivered'
             GROUP BY product_id, product_name ORDER BY qty DESC, revenue DESC LIMIT :limit",
            ['limit' => $limit]
        );
    }

    /** @return array{0: string, 1: array<string, mixed>} WHERE fragment on delivered orders o + params */
    private function reportWhere(string $search, ?string $from, ?string $to): array
    {
        $clauses = ["o.status = 'delivered'"];
        $params = [];
        if ($search !== '') {
            $clauses[] = '(o.order_code LIKE :q OR o.recipient_name LIKE :q2 OR o.shipping_phone LIKE :q3)';
            $params['q'] = $params['q2'] = $params['q3'] = $this->likeTerm($search);
        }
        if ($from !== null) {
            $clauses[] = 'o.created_at >= :from';
            $params['from'] = $from;
        }
        if ($to !== null) {
            $clauses[] = 'o.created_at < DATE_ADD(:to, INTERVAL 1 DAY)';
            $params['to'] = $to;
        }

        return [implode(' AND ', $clauses), $params];
    }

    /** KPI cards for the sales report over delivered orders only, honoring search + date filters. @return array{revenue: int, orders: int, delivered_orders: int} */
    public function reportSummary(string $search, ?string $from, ?string $to): array
    {
        [$where, $params] = $this->reportWhere($search, $from, $to);
        $deliveredCount = (int) $this->fetchValue(
            "SELECT COUNT(*) FROM orders o WHERE {$where}",
            $params
        );
        $revenue = (int) $this->fetchValue(
            "SELECT COALESCE(SUM(oi.unit_price * oi.quantity), 0) FROM order_items oi JOIN orders o ON o.id = oi.order_id
             WHERE oi.status = 'delivered' AND {$where}",
            $params
        );

        return [
            'revenue' => $revenue,
            'orders' => $deliveredCount,
            'delivered_orders' => $deliveredCount,
        ];
    }

    /** Revenue per month for the last $months months (including the current one), zero filled. @return array<string, int> */
    public function monthlySeries(int $months): array
    {
        $from = date('Y-m-01', strtotime('-' . ($months - 1) . ' months'));
        $rows = $this->fetchAll(
            "SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS bucket, SUM(oi.unit_price * oi.quantity) AS revenue
             FROM order_items oi JOIN orders o ON o.id = oi.order_id
             WHERE oi.status = 'delivered' AND o.created_at >= :from
             GROUP BY bucket",
            ['from' => $from]
        );
        $map = array_column($rows, 'revenue', 'bucket');

        $series = [];
        for ($t = strtotime($from); $t <= time(); $t = strtotime('+1 month', $t)) {
            $key = date('Y-m', $t);
            $series[$key] = (int) ($map[$key] ?? 0);
        }

        return $series;
    }

    /** Revenue per day for the last $days days (including today), zero filled. @return array<string, int> */
    public function dailySeries(int $days): array
    {
        $from = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));
        $rows = $this->fetchAll(
            "SELECT DATE(o.created_at) AS bucket, SUM(oi.unit_price * oi.quantity) AS revenue
             FROM order_items oi JOIN orders o ON o.id = oi.order_id
             WHERE oi.status = 'delivered' AND o.created_at >= :from
             GROUP BY bucket",
            ['from' => $from]
        );
        $map = array_column($rows, 'revenue', 'bucket');

        $series = [];
        for ($t = strtotime($from); $t <= strtotime('today'); $t += 86400) {
            $key = date('Y-m-d', $t);
            $series[$key] = (int) ($map[$key] ?? 0);
        }

        return $series;
    }

    /** Products with the most reviews (regardless of rating). @return array<int, array{name: string, rating_avg: float, rating_count: int}> */
    public function mostReviewed(int $limit): array
    {
        return $this->fetchAll(
            'SELECT name, rating_avg, rating_count FROM products
             WHERE rating_count > 0
             ORDER BY rating_count DESC, rating_avg DESC LIMIT :limit',
            ['limit' => $limit]
        );
    }

    /** Most recent order status changes, across all orders. @return array<int, array{order_code: string, to_status: string, created_at: string}> */
    public function recentActivity(int $limit): array
    {
        return $this->fetchAll(
            'SELECT o.order_code, h.to_status, h.created_at
             FROM order_status_histories h JOIN orders o ON o.id = h.order_id
             ORDER BY h.id DESC LIMIT :limit',
            ['limit' => $limit]
        );
    }

    /** @return array<int, array<string, mixed>> */
    public function recentOrders(int $limit): array
    {
        return $this->fetchAll(
            'SELECT id, order_code, recipient_name, status, created_at, total_amount
             FROM orders ORDER BY id DESC LIMIT :limit',
            ['limit' => $limit]
        );
    }
}