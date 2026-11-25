<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\ErrorHandler;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use DomainException;

final class OrderController extends BaseController
{
    public function __construct(private readonly OrderRepository $orders = new OrderRepository())
    {
    }

    public function index(Request $request): Response
    {
        $search = trim((string) $request->getQuery('q', ''));
        $status = (string) $request->getQuery('status', '');
        if ($status !== 'in_progress' && !in_array($status, \App\Enums\OrderStatus::values(), true)) {
            $status = '';
        }

        $from = $this->normalizeDate((string) $request->getQuery('from', ''));
        $to = $this->normalizeDate((string) $request->getQuery('to', ''));

        $total = $this->orders->adminCount($search, $status, $from, $to);
        $paginator = Paginator::fromRequest($request, $total, 10);

        $queryParams = array_filter([
            'q' => $search !== '' ? $search : null,
            'status' => $status !== '' ? $status : null,
            'from' => $from,
            'to' => $to,
            'page' => $paginator->getCurrentPage() > 1 ? $paginator->getCurrentPage() : null,
        ], static fn ($v): bool => $v !== null);

        $currentListUrl = url('admin/orders') . ($queryParams !== [] ? '?' . http_build_query($queryParams) : '');

        return $this->render('admin.orders.index', [
            'title' => 'Đơn hàng',
            'rows' => $this->orders->adminList($search, $status, $paginator->getLimit(), $paginator->getOffset(), $from, $to),
            'paginator' => $paginator,
            'search' => $search,
            'status' => $status,
            'from' => $from ?? '',
            'to' => $to ?? '',
            'currentListUrl' => $currentListUrl,
        ], 'layouts.admin');
    }

    public function show(Request $request, string $id): Response
    {
        $order = $this->orders->findById((int) $id);
        if ($order === null) {
            return ErrorHandler::render(404, $request);
        }

        $returnUrl = $this->sanitizeReturnUrl((string) $request->getQuery('return_url', ''));

        return $this->render('admin.orders.show', [
            'title' => 'Đơn ' . $order['order_code'],
            'order' => $order,
            'items' => $this->orders->items((int) $id),
            'histories' => $this->orders->histories((int) $id),
            'returnUrl' => $returnUrl,
        ], 'layouts.admin');
    }

    public function updateStatus(Request $request, string $id): Response
    {
        $orderId = (int) $id;
        $returnUrl = $this->sanitizeReturnUrl((string) $request->getInput('return_url', ''));
        try {
            (new OrderService())->advanceOrder($orderId, (int) Auth::id(), (string) $request->getInput('status'));
            $this->flash('success', 'Cập nhật trạng thái thành công.');
        } catch (DomainException $e) {
            $this->flash('danger', $e->getMessage());
        }

        return $this->redirect($this->orderDetailRedirectUrl($orderId, $returnUrl));
    }

    public function confirmPayment(Request $request, string $id): Response
    {
        $orderId = (int) $id;
        $returnUrl = $this->sanitizeReturnUrl((string) $request->getInput('return_url', ''));
        try {
            (new OrderService())->confirmPayment($orderId, (int) Auth::id());
            $this->flash('success', 'Đã xác nhận nhận tiền.');
        } catch (DomainException $e) {
            $this->flash('danger', $e->getMessage());
        }

        return $this->redirect($this->orderDetailRedirectUrl($orderId, $returnUrl));
    }

    private function orderDetailRedirectUrl(int $orderId, string $returnUrl): string
    {
        $target = url('admin/orders/' . $orderId);
        $defaultPath = (string) parse_url(url('admin/orders'), PHP_URL_PATH);
        $returnPath = (string) parse_url($returnUrl, PHP_URL_PATH);

        if ($returnPath !== '' && rtrim($returnPath, '/') !== rtrim($defaultPath, '/')) {
            $target .= '?return_url=' . urlencode($returnUrl);
        }

        return $target;
    }

    private function normalizeDate(string $raw): ?string
    {
        $raw = trim($raw);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return null;
        }
        $dt = \DateTimeImmutable::createFromFormat('Y-m-d', $raw);

        return ($dt !== false && $dt->format('Y-m-d') === $raw) ? $raw : null;
    }

    private function sanitizeReturnUrl(string $raw): string
    {
        $fallback = url('admin/orders');
        $raw = trim($raw);
        if ($raw === '') {
            return $fallback;
        }

        // Chặn protocol-relative URL (//evil.com) hoặc scheme lạ (javascript:, http://...)
        if (str_starts_with($raw, '//') || preg_match('/^[a-z][a-z0-9+.-]*:/i', $raw) === 1) {
            return $fallback;
        }

        // Cho phép đường dẫn nội bộ bắt đầu bằng '/' (ví dụ /admin/report?... hoặc /bakehouse/public/index.php/admin/report...)
        if (str_starts_with($raw, '/')) {
            $rawPath = parse_url($raw, PHP_URL_PATH) ?: '';
            if ($rawPath !== '' && str_contains($rawPath, '/admin')) {
                return $raw;
            }
        }

        // Nếu truyền URL đầy đủ cùng host của ứng dụng
        $appHost = parse_url(url('/'), PHP_URL_HOST);
        $rawHost = parse_url($raw, PHP_URL_HOST);
        $rawPath = parse_url($raw, PHP_URL_PATH) ?: '';
        if ($appHost !== null && $rawHost !== null && strcasecmp((string) $appHost, (string) $rawHost) === 0 && str_contains($rawPath, '/admin')) {
            $query = parse_url($raw, PHP_URL_QUERY);
            $fragment = parse_url($raw, PHP_URL_FRAGMENT);

            return $rawPath . ($query ? '?' . $query : '') . ($fragment ? '#' . $fragment : '');
        }

        return $fallback;
    }
}