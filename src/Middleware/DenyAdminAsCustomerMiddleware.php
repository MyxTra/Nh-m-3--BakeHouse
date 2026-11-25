<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

/** Blocks customer-only actions (cart, checkout, order tracking, profile updates, reviews) for admins browsing the storefront. */
final class DenyAdminAsCustomerMiddleware implements MiddlewareInterface
{
    private const MESSAGE = 'Tài khoản quản trị không thể thực hiện thao tác này trên website khách hàng. Vui lòng dùng tài khoản khách hàng để trải nghiệm chức năng này.';

    public function handle(Request $request): ?Response
    {
        if (!Auth::isAdmin()) {
            return null;
        }

        if ($request->isAjax()) {
            return Response::json(['ok' => false, 'message' => self::MESSAGE], 403);
        }

        Session::flash('warning', self::MESSAGE);

        $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        $parts = parse_url($referer);
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($parts !== false && isset($parts['path']) && (!isset($parts['host']) || $parts['host'] === strtok($host, ':'))) {
            $target = $parts['path'] . (isset($parts['query']) ? '?' . $parts['query'] : '');

            return Response::redirect($target);
        }

        return Response::redirect(url('/'));
    }
}