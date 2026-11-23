<?php
/** @var string $content */ /** @var string|null $title */
$flashes = \App\Core\Session::pullFlash();
$admin = auth_user();
// Each group: [label or null, items]; each item: [routePath, label, icon, exact match?, target?]
$nav = [
    [null, [
        ['admin', 'Tổng quan', 'bi-pie-chart', true],
        ['admin/products', 'Nguyên vật liệu', 'bi-basket3', false],
        ['admin/orders', 'Đơn hàng', 'bi-journal-text', false],
        ['admin/report', 'Báo cáo', 'bi-printer', false],
    ]],
    ['Danh mục chung', [
        ['admin/categories', 'Danh mục', 'bi-grid', false],
        ['admin/brands', 'Thương hiệu', 'bi-award', false],
        ['admin/banners', 'Banner', 'bi-image', false],
    ]],
    ['Hệ thống', [
        ['admin/users', 'Người dùng', 'bi-people', false],
        ['admin/reviews', 'Đánh giá', 'bi-star', false],
        ['admin/contacts', 'Liên hệ', 'bi-envelope', false],
    ]],
];
$path = current_path();
$renderNav = static function () use ($nav, $path): void {
    foreach ($nav as [$label, $items]) {
        if ($label !== null) {
            echo '<div class="bh-admin__nav-label">' . e($label) . '</div>';
        }
        foreach ($items as $item) {
            $routePath = '/' . ltrim((string) $item[0], '/');
            $href = url((string) $item[0]);
            $active = $item[3] ? $path === $routePath : str_starts_with($path, $routePath);
            $target = isset($item[4]) ? ' target="' . e((string) $item[4]) . '" rel="noopener"' : '';
            echo '<a class="' . ($active ? 'is-active' : '') . '" href="' . e($href) . '"' . ($active ? ' aria-current="page"' : '') . $target . '><i class="bi ' . e((string) $item[2]) . '"></i> ' . e((string) $item[1]) . '</a>';
        }
    }
};
$displayName = trim((string) ($admin['full_name'] ?? '')) ?: 'Quản trị viên';
$adminEmail = (string) ($admin['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(\App\Core\Csrf::token()) ?>">
    <title><?= e(($title ?? '') !== '' ? $title . ' | Quản trị BakeHouse' : 'Quản trị BakeHouse') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap&subset=vietnamese" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(asset('assets/css/tokens.css')) ?>" rel="stylesheet">
    <link href="<?= e(asset('assets/css/app.css')) ?>" rel="stylesheet">
    <link href="<?= e(asset('assets/css/admin.css')) ?>" rel="stylesheet">
</head>
<body>
<div class="bh-admin">
    <aside class="bh-admin__sidebar d-none d-lg-flex">
        <a class="bh-logo bh-admin__brand" href="<?= e(url('admin')) ?>">Bake<span class="bh-logo__accent">House</span></a>
        <nav class="bh-admin__nav" aria-label="Điều hướng quản trị"><?php $renderNav(); ?></nav>
    </aside>
    <div class="offcanvas offcanvas-start bh-offcanvas" tabindex="-1" id="bhAdminMenu" aria-label="Menu quản trị" style="width:260px">
        <div class="offcanvas-header">
            <span class="bh-logo">Bake<span class="bh-logo__accent">House</span></span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>
        <div class="offcanvas-body p-0"><nav class="bh-admin__nav"><?php $renderNav(); ?></nav></div>
    </div>
    <div class="bh-admin__main">
        <header class="bh-admin__topbar">
            <div class="d-flex align-items-center gap-2 min-w-0">
                <button class="bh-btn bh-btn--outline bh-btn--sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#bhAdminMenu" aria-label="Mở menu"><i class="bi bi-list"></i></button>
                <h1 class="text-truncate"><?= e($title ?? '') ?></h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a class="bh-admin__view-site" href="<?= e(url('')) ?>"><i class="bi bi-house"></i> Xem website</a>
                <div class="dropdown bh-admin__user">
                <button class="bh-admin__avatar" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Tài khoản quản trị"><i class="bi bi-person-fill"></i></button>
                <ul class="dropdown-menu dropdown-menu-end bh-account-menu">
                    <li class="bh-account-menu__header">
                        <div class="bh-account-menu__avatar"><i class="bi bi-person-fill"></i></div>
                        <div class="min-w-0">
                            <div class="bh-account-menu__name"><?= e($displayName) ?></div>
                            <?php if ($adminEmail !== '') : ?><div class="bh-account-menu__sub"><?= e($adminEmail) ?></div><?php endif; ?>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider bh-account-menu__divider"></li>
                    <li><form method="post" action="<?= e(url('admin/logout')) ?>"><?= csrf_field() ?><button class="dropdown-item bh-account-menu__item bh-account-menu__item--danger" type="submit"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button></form></li>
                </ul>
                </div>
            </div>
        </header>
        <div class="bh-admin__content"><?= $content ?></div>
    </div>
</div>
<?php partial('confirm-modal'); ?>
<div class="toast-container position-fixed end-0 p-3 bh-toasts bh-toasts--admin" id="bhToasts" style="z-index:1100">
    <?php foreach ($flashes as $flash) : ?><span data-flash="<?= e((string) ($flash['type'] ?? 'info')) ?>" data-message="<?= e((string) ($flash['message'] ?? '')) ?>"></span><?php endforeach; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('assets/js/app.js')) ?>"></script>
</body>
</html>