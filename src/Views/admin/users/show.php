<?php /** @var array $user */ /** @var array{orders:int, spent:int, reviews:int} $activity */ /** @var bool $isSelf */
$isAdmin = ($user['role'] ?? '') === 'admin';
$isActive = (int) ($user['is_active'] ?? 0) === 1;
$id = (int) $user['id']; ?>
<div class="mb-3"><a class="adm-back" href="<?= e(url('admin/users')) ?>"><i class="bi bi-arrow-left"></i> Danh sách người dùng</a></div>
<div class="adm-contact-detail">
    <section class="adm-card">
        <div class="d-flex align-items-center gap-3 mb-3">
            <img class="adm-avatar" style="width:56px;height:56px" src="<?= e(img_url($user['avatar_path'] ?? null)) ?>" alt="">
            <div>
                <h2 class="adm-contact-detail__title mb-0"><?= e($user['full_name'] !== null && $user['full_name'] !== '' ? $user['full_name'] : 'Chưa cập nhật') ?></h2>
                <div class="bh-muted"><?= e($user['email'] ?: $user['phone'] ?: '—') ?></div>
            </div>
        </div>
        <dl class="adm-contact-detail__info">
            <dt>SĐT nhận hàng</dt><dd><?= e($user['shipping_phone'] ?: '—') ?></dd>
            <dt>Địa chỉ</dt><dd><?= e($user['shipping_address'] ?: '—') ?></dd>
            <dt>Vai trò</dt><dd><?= $isAdmin ? 'Quản trị viên' : 'Người dùng' ?></dd>
            <dt>Trạng thái</dt><dd><?= $isActive ? 'Hoạt động' : 'Đã khóa' ?></dd>
            <dt>Ngày tạo</dt><dd><?= e(date('d/m/Y H:i', strtotime((string) $user['created_at']))) ?></dd>
            <dt>Số đơn hàng</dt><dd><?= (int) $activity['orders'] ?></dd>
            <dt>Đã chi tiêu</dt><dd><?= e(format_vnd((int) $activity['spent'])) ?></dd>
            <dt>Số đánh giá</dt><dd><?= (int) $activity['reviews'] ?></dd>
        </dl>
    </section>

    <?php if (!$isSelf) : ?>
    <aside class="adm-card">
        <h2 class="adm-contact-detail__title mb-3">Quản lý tài khoản</h2>
        <form method="post" action="<?= e(url('admin/users/' . $id . '/role')) ?>" class="d-flex gap-2 mb-3">
            <?= csrf_field() ?>
            <select class="form-select" name="role" aria-label="Vai trò">
                <option value="user"<?= !$isAdmin ? ' selected' : '' ?>>Người dùng</option>
                <option value="admin"<?= $isAdmin ? ' selected' : '' ?>>Quản trị viên</option>
            </select>
            <button class="bh-btn bh-btn--primary text-nowrap" type="submit">Đổi vai trò</button>
        </form>
        <form method="post" action="<?= e(url('admin/users/' . $id . '/toggle-active')) ?>" data-confirm="<?= $isActive ? 'Vô hiệu hóa tài khoản này?' : 'Mở khóa tài khoản này?' ?>">
            <?= csrf_field() ?>
            <button class="bh-btn bh-btn--dark w-100 mb-3" type="submit"><?= $isActive ? 'Vô hiệu hóa tài khoản' : 'Mở khóa tài khoản' ?></button>
        </form>
        <a class="bh-btn bh-btn--outline" href="<?= e(url('admin/users')) ?>">Quay lại</a>
    </aside>
    <?php endif; ?>
</div>