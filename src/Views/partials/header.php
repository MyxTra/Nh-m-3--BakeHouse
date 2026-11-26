<?php
$navItems = [
    ['', 'Trang chủ', '/'],
    ['about', 'Giới thiệu', '/about'],
    ['products', 'Sản phẩm', '/products'],
    ['gallery', 'Thư viện', '/gallery'],
    ['blog', 'Blog', '/blog'],
    ['contact', 'Liên hệ', '/contact'],
];
$user = auth_user();
$cartCount = $user ? cart_count() : 0;
$categoryGroups = nav_category_groups();
$unreadCount = $user ? notification_unread_count() : 0;
$recentNotifications = $user ? notification_recent(6) : [];
?>
<header class="bh-header">
    <div class="container bh-header__inner d-flex align-items-center justify-content-between">
        <a class="bh-logo" href="<?= e(url('')) ?>">Bake<span class="bh-logo__accent">House</span></a>
        <nav class="bh-nav d-none d-lg-flex" aria-label="Điều hướng chính">
            <?php foreach ($navItems as [$routePath, $label, $prefix]) :
                $navHref = url($routePath); ?>
                <?php if ($routePath === 'products' && $categoryGroups !== []) : ?>
                    <div class="bh-nav__item bh-nav__item--mega">
                        <a class="bh-nav__link<?= nav_active($prefix) ?>" href="<?= e($navHref) ?>"><?= e($label) ?></a>
                        <div class="bh-mega">
                            <div class="bh-mega__inner">
                                <?php foreach ($categoryGroups as $group) : ?>
                                    <div class="bh-mega__col">
                                        <div class="bh-mega__head"><i class="bi <?= e($group['icon']) ?>"></i> <?= e($group['label']) ?></div>
                                        <ul class="bh-mega__list">
                                            <?php foreach ($group['items'] as $category) : ?>
                                                <li><a href="<?= e(url('products') . '?category=' . urlencode((string) $category['slug'])) ?>"><?= e($category['name']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <a class="bh-nav__link<?= nav_active($prefix) ?>" href="<?= e($navHref) ?>"><?= e($label) ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <a class="bh-icon-btn" href="<?= e(url('products')) ?>" aria-label="Tìm kiếm sản phẩm"><i class="bi bi-search"></i></a>
            <?php if ($user) : ?>
                <a class="bh-icon-btn" href="<?= e(url('cart')) ?>" aria-label="Giỏ hàng">
                    <i class="bi bi-cart-fill"></i>
                    <span class="bh-cart-badge" style="<?= $cartCount > 0 ? '' : 'display:none' ?>"><?= $cartCount ?></span>
                </a>
                <div class="dropdown">
                    <button class="bh-icon-btn" type="button" id="bhNotifBtn" data-bs-toggle="dropdown" aria-label="Thông báo">
                        <i class="bi bi-bell-fill"></i>
                        <span class="bh-notif-badge" id="bhNotifBadge" style="<?= $unreadCount > 0 ? '' : 'display:none' ?>"><?= $unreadCount ?></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end bh-notif-menu" aria-labelledby="bhNotifBtn">
                        <div class="bh-notif-menu__head">Thông báo</div>
                        <?php if ($recentNotifications === []) : ?>
                            <div class="bh-notif-menu__empty">Chưa có thông báo nào.</div>
                        <?php else : ?>
                            <?php foreach ($recentNotifications as $n) :
                                $rawLink = trim((string) ($n['link'] ?? ''));
                                $notifHref = $rawLink === ''
                                    ? url('notifications')
                                    : (preg_match('#^https?://#i', $rawLink) ? $rawLink : url(ltrim($rawLink, '/')));
                                $createdAt = (string) ($n['created_at'] ?? '');
                                $formattedTime = $createdAt !== '' ? date('H:i d/m/Y', strtotime($createdAt)) : ''; ?>
                                <a class="bh-notif-menu__item<?= (int) $n['is_read'] === 0 ? ' is-unread' : '' ?>" href="<?= e($notifHref) ?>">
                                    <span class="bh-notif-menu__icon"><i class="bi <?= e(notification_icon((string) $n['type'])) ?>"></i></span>
                                    <span class="min-w-0">
                                        <span class="bh-notif-menu__title"><?= e($n['title']) ?></span>
                                        <?php if ($formattedTime !== '') : ?>
                                            <br><small class="bh-notif-menu__time"><?= e($formattedTime) ?></small>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <a class="bh-notif-menu__all" href="<?= e(url('notifications')) ?>">Xem tất cả</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="bh-icon-btn" type="button" data-bs-toggle="dropdown" aria-label="Tài khoản"><i class="bi bi-person-fill"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end bh-account-menu">
                        <li class="bh-account-menu__header">
                            <div class="bh-account-menu__avatar"><i class="bi bi-person-fill"></i></div>
                            <div class="min-w-0">
                                <div class="bh-account-menu__name"><?= e($user['full_name'] ?: ($user['email'] ?? $user['phone'])) ?></div>
                                <?php if ($user['full_name'] && ($user['email'] ?? $user['phone'])) : ?>
                                    <div class="bh-account-menu__sub"><?= e($user['email'] ?? $user['phone']) ?></div>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider bh-account-menu__divider"></li>
                        <li><a class="dropdown-item bh-account-menu__item" href="<?= e(url('account')) ?>"><i class="bi bi-person-circle"></i> Tài khoản của tôi</a></li>
                        <li><a class="dropdown-item bh-account-menu__item" href="<?= e(url('orders')) ?>"><i class="bi bi-receipt"></i> Đơn hàng của tôi</a></li>
                        <li><a class="dropdown-item bh-account-menu__item" href="<?= e(url('contacts')) ?>"><i class="bi bi-envelope"></i> Liên hệ của tôi</a></li>
                        <?php if (($user['role'] ?? null) === 'admin') : ?>
                            <li><a class="dropdown-item bh-account-menu__item" href="<?= e(url('admin')) ?>"><i class="bi bi-speedometer2"></i> Trang quản trị</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider bh-account-menu__divider"></li>
                        <li>
                            <form method="post" action="<?= e(url('logout')) ?>"><?= csrf_field() ?>
                                <button class="dropdown-item bh-account-menu__item bh-account-menu__item--danger" type="submit"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            <?php else : ?>
                <a class="bh-btn bh-btn--header bh-btn--light" href="<?= e(url('login')) ?>">Đăng nhập</a>
                <a class="bh-btn bh-btn--header bh-btn--primary" href="<?= e(url('register')) ?>">Đăng ký</a>
            <?php endif; ?>
            <button class="bh-icon-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#bhMenu" aria-label="Menu"><i class="bi bi-list"></i></button>
        </div>
    </div>
</header>
<div class="offcanvas offcanvas-start bh-offcanvas" tabindex="-1" id="bhMenu">
    <div class="offcanvas-header">
        <span class="bh-logo">Bake<span class="bh-logo__accent">House</span></span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>
    <div class="offcanvas-body">
        <?php foreach ($navItems as [$routePath, $label, $prefix]) : ?>
            <a class="bh-nav__link<?= nav_active($prefix) ?>" href="<?= e(url($routePath)) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </div>
</div>