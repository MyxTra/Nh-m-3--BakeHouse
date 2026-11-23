<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Env;
use App\Core\Session;

if (!function_exists('app_base_path')) {
    function app_base_path(): string
    {
        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($baseDir === '' || $baseDir === '/' || $baseDir === '.') {
            return '';
        }

        return '/' . trim($baseDir, '/');
    }
}

if (!function_exists('app_route_prefix')) {
    /**
     * Route prefix for non-mod_rewrite front-controller URLs (e.g. /BakeHouse/public/index.php),
     * while avoiding duplicating it if $path already starts with it.
     */
    function app_route_prefix(): string
    {
        $envUrl = rtrim((string) Env::get('APP_URL', ''), '/');
        if ($envUrl !== '') {
            return $envUrl;
        }

        $base = app_base_path();
        $scriptName = '/' . ltrim(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if ($scriptName !== '/' && preg_match('#/index\.php$#i', $scriptName)) {
            if ($base !== '' && !str_ends_with(strtolower($base), '/index.php')) {
                return $base . '/index.php';
            }
        }

        return $base;
    }
}

if (!function_exists('request_route_path')) {
    function request_route_path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = '/' . trim(rawurldecode((string) $path), '/');

        $scriptName = '/' . ltrim(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $base = app_base_path();

        foreach (array_filter([$scriptName, $base], static fn (string $p): bool => $p !== '' && $p !== '/' && $p !== '.') as $prefix) {
            $normalized = '/' . trim($prefix, '/');
            if ($path === $normalized) {
                $path = '/';
                break;
            }
            if (str_starts_with($path, $normalized . '/')) {
                $path = substr($path, strlen($normalized));
                break;
            }
        }

        $path = preg_replace('#^/(?:public/)?index\.php(?=/|$)#i', '', $path) ?: '/';

        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        if (preg_match('#^(?:https?:)?//#i', $path)) {
            return $path;
        }

        $prefix = app_route_prefix();
        $cleanPath = '/' . ltrim($path, '/');

        if ($prefix !== '' && !str_starts_with($prefix, 'http') && ($cleanPath === $prefix || str_starts_with($cleanPath, $prefix . '/'))) {
            return $cleanPath === $prefix ? $prefix . '/' : $cleanPath;
        }

        $cleanPath = preg_replace('#^/(?:public/)?index\.php(?=/|$)#i', '', $cleanPath) ?: '/';
        $cleanPath = '/' . ltrim($cleanPath, '/');

        if ($cleanPath === '/') {
            return ($prefix !== '' ? $prefix : '') . '/';
        }

        return $prefix . $cleanPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path; // absolute URL (e.g. demo images hosted externally)
        }

        $relative = ltrim($path, '/');
        $fullPath = app_base_path() . '/' . $relative;

        // CSS/JS: gắn ?v=<thời điểm sửa file> để trình duyệt tải bản mới ngay sau mỗi lần sửa.
        if (preg_match('#\.(css|js)$#i', $relative)) {
            $mtime = @filemtime(BASE_PATH . '/public/' . $relative);
            if ($mtime !== false) {
                $fullPath .= '?v=' . $mtime;
            }
        }

        return $fullPath;
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        static $old = null;
        $old ??= Session::pullOld();

        return e($old[$key] ?? $default);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('format_vnd')) {
    function format_vnd(int|float $amount): string
    {
        return number_format((float) $amount, 0, ',', '.') . ' ₫';
    }
}

if (!function_exists('lang')) {
    /** Look up a translation by dot key from src/Lang/vi.php. */
    function lang(string $key, string $default = ''): string
    {
        static $messages = null;
        $messages ??= require BASE_PATH . '/src/Lang/vi.php';

        $value = $messages;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default !== '' ? $default : $key;
            }
            $value = $value[$segment];
        }

        return is_string($value) ? $value : $default;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        return \App\Helpers\Slugger::slugify($text);
    }
}

if (!function_exists('image_url')) {
    function image_url(?string $path, string $fallback = 'assets/img/placeholder.svg'): string
    {
        return asset($path !== null && $path !== '' ? $path : $fallback);
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        return \App\Core\Auth::user();
    }
}

if (!function_exists('field_error')) {
    function field_error(string $field): string
    {
        static $errors = null;
        $errors ??= \App\Core\Session::pullErrors();

        return isset($errors[$field]) ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>' : '';
    }
}

if (!function_exists('is_path')) {
    function is_path(string $prefix): bool
    {
        $path = request_route_path();

        return $prefix === '/' ? $path === '/' : str_starts_with($path, $prefix);
    }
}

if (!function_exists('config_value')) {
    function config_value(string $key, ?string $default = null): ?string
    {
        return \App\Core\Env::get($key, $default);
    }
}

if (!function_exists('img_url')) {
    /** Public URL for an uploaded/static image path, with a placeholder fallback. */
    function img_url(?string $path, string $fallback = 'assets/img/product-placeholder.svg'): string
    {
        if ($path === null || $path === '') {
            return asset($fallback);
        }

        return asset($path);
    }
}

if (!function_exists('form_error')) {
    function form_error(string $field): string
    {
        static $errors = null;
        $errors ??= \App\Core\Session::pullErrors();

        return isset($errors[$field])
            ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>'
            : '';
    }
}

if (!function_exists('query_url')) {
    /** Build a URL for the current path, merging/removing query parameters. */
    function query_url(array $merge = [], array $remove = []): string
    {
        $params = array_merge($_GET, $merge);
        foreach ($remove as $key) {
            unset($params[$key]);
        }
        $params = array_filter($params, static fn ($v): bool => $v !== '' && $v !== null);
        $baseRoutePath = request_route_path();
        $path = url($baseRoutePath);

        return $path . ($params === [] ? '' : '?' . http_build_query($params));
    }
}

if (!function_exists('current_path')) {
    function current_path(): string
    {
        return request_route_path();
    }
}

if (!function_exists('nav_active')) {
    function nav_active(string $prefix): string
    {
        $path = current_path();
        $active = $prefix === '/' ? $path === '/' : str_starts_with($path, $prefix);

        return $active ? ' bh-nav__link--active' : '';
    }
}

if (!function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        $enum = \App\Enums\OrderStatus::from($status);

        return '<span class="badge ' . $enum->badgeClass() . '">' . e($enum->label()) . '</span>';
    }
}

if (!function_exists('nav_category_groups')) {
    /**
     * Active product categories grouped into columns for the "Sản phẩm" hover mega-menu.
     * The grouping is a fixed, hand-picked layout keyed by slug; any category that doesn't
     * match a known slug (newly added in admin) falls into a trailing "Khác" column so the
     * menu never silently drops it.
     *
     * @return array<int, array{label: string, icon: string, items: array<int, array<string, mixed>>}>
     */
    function nav_category_groups(): array
    {
        try {
            $categories = (new \App\Repositories\CategoryRepository())->activeWithCounts();
        } catch (\Throwable) {
            return [];
        }
        if ($categories === []) {
            return [];
        }

        $groups = [
            ['label' => 'Nguyên liệu khô', 'icon' => 'bi-basket2', 'slugs' => ['bot-ngu-coc', 'chat-tao-ngot', 'phu-gia-chat-tao-no']],
            ['label' => 'Bơ, sữa & trứng', 'icon' => 'bi-egg-fried', 'slugs' => ['chat-beo', 'sua-kem-trung']],
            ['label' => 'Hương liệu & trang trí', 'icon' => 'bi-palette', 'slugs' => ['huong-lieu-mau-thuc-pham', 'socola-hat-trai-cay-say']],
            ['label' => 'Dụng cụ làm bánh', 'icon' => 'bi-tools', 'slugs' => ['dung-cu-lam-banh']],
        ];

        $bySlug = [];
        foreach ($categories as $category) {
            $bySlug[$category['slug']] = $category;
        }

        $result = [];
        $used = [];
        foreach ($groups as $group) {
            $items = [];
            foreach ($group['slugs'] as $slug) {
                if (isset($bySlug[$slug])) {
                    $items[] = $bySlug[$slug];
                    $used[$slug] = true;
                }
            }
            if ($items !== []) {
                $result[] = ['label' => $group['label'], 'icon' => $group['icon'], 'items' => $items];
            }
        }

        $rest = array_values(array_filter($categories, static fn (array $c): bool => !isset($used[$c['slug']])));
        if ($rest !== []) {
            $result[] = ['label' => 'Khác', 'icon' => 'bi-grid', 'items' => $rest];
        }

        return $result;
    }
}

if (!function_exists('cart_count')) {
    function cart_count(): int
    {
        $id = \App\Core\Auth::id();
        if ($id === null) {
            return 0;
        }
        try {
            return (new \App\Repositories\CartRepository())->countItems($id);
        } catch (\Throwable) {
            return 0;
        }
    }
}

if (!function_exists('notification_unread_count')) {
    function notification_unread_count(): int
    {
        $id = \App\Core\Auth::id();
        if ($id === null) {
            return 0;
        }
        try {
            return (new \App\Repositories\NotificationRepository())->unreadCount($id);
        } catch (\Throwable) {
            return 0;
        }
    }
}

if (!function_exists('notification_recent')) {
    /** @return array<int, array<string, mixed>> */
    function notification_recent(int $limit = 8): array
    {
        $id = \App\Core\Auth::id();
        if ($id === null) {
            return [];
        }
        try {
            return (new \App\Repositories\NotificationRepository())->recentForUser($id, $limit);
        } catch (\Throwable) {
            return [];
        }
    }
}

if (!function_exists('notification_icon')) {
    function notification_icon(string $type): string
    {
        return match ($type) {
            'order_placed' => 'bi-bag-check',
            'order_processing' => 'bi-clipboard-check',
            'order_shipped' => 'bi-truck',
            'order_delivered' => 'bi-check-circle',
            'review_reminder' => 'bi-star',
            'contact_replied' => 'bi-envelope-check',
            'review_replied' => 'bi-chat-left-text',
            default => 'bi-bell',
        };
    }
}

if (!function_exists('time_ago')) {
    function time_ago(string $datetime): string
    {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) {
            return 'Vừa xong';
        }
        if ($diff < 3600) {
            return intdiv($diff, 60) . ' phút trước';
        }
        if ($diff < 86400) {
            return intdiv($diff, 3600) . ' giờ trước';
        }
        if ($diff < 2592000) {
            return intdiv($diff, 86400) . ' ngày trước';
        }

        return date('d/m/Y', strtotime($datetime));
    }
}

if (!function_exists('partial')) {
    /** Include a view from src/Views/partials with the given variables. */
    function partial(string $name, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require BASE_PATH . '/src/Views/partials/' . $name . '.php';
    }
}

if (!function_exists('render_stars')) {
    function render_stars(float $rating): string
    {
        $html = '<span class="bh-stars" aria-label="' . e(number_format($rating, 1)) . ' trên 5 sao">';
        for ($i = 1; $i <= 5; $i++) {
            $icon = $rating >= $i ? 'bi-star-fill' : ($rating >= $i - 0.5 ? 'bi-star-half' : 'bi-star');
            $html .= '<i class="bi ' . $icon . '"></i>';
        }

        return $html . '</span>';
    }
}

if (!function_exists('method_field')) {
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e($method) . '">';
    }
}