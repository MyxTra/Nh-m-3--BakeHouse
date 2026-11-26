<?php /** @var array $rows */ /** @var \App\Core\Paginator $paginator */ /** @var string $search */ /** @var string $role */ /** @var string $active */
$selfId = \App\Core\Auth::id();
$rootAdminId = 1;
$actorIsRoot = $selfId !== null && (int) $selfId === $rootAdminId;
$currentPage = isset($_GET['page']) && ctype_digit((string) $_GET['page']) && (int) $_GET['page'] > 1
    ? (int) $_GET['page']
    : 1;
$filterQuery = array_filter([
    'q' => $search !== '' ? $search : null,
    'role' => $role !== '' ? $role : null,
    'active' => $active !== '' ? $active : null,
    'page' => $currentPage > 1 ? $currentPage : null,
], static fn (mixed $v): bool => $v !== null);
$querySuffix = $filterQuery !== [] ? '?' . http_build_query($filterQuery) : '';
partial('admin-filter', [
    'search' => $search, 'placeholder' => 'Tìm theo email, SĐT, họ tên…',
    'selects' => [
        'role' => ['Mọi vai trò', ['user' => 'Người dùng', 'admin' => 'Quản trị viên'], $role],
        'active' => ['Mọi trạng thái', ['1' => 'Hoạt động', '0' => 'Đã khóa'], $active],
    ],
]); ?>
<div class="adm-card adm-card--flush">
    <table class="table adm-table adm-table--stack">
        <thead><tr><th>#</th><th>Họ tên</th><th>Email / SĐT</th><th>Vai trò</th><th>Trạng thái</th><th>Ngày tạo</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $u) :
            $userId = (int) $u['id'];
            $isAdmin = ($u['role'] ?? '') === 'admin';
            $isActive = (int) ($u['is_active'] ?? 0) === 1;
            $isSelf = $selfId !== null && (int) $selfId === $userId;
            $isRootTarget = $userId === $rootAdminId;
            $canToggleActive = !$isSelf && !$isRootTarget && ($actorIsRoot || !$isAdmin);
            $detailUrl = url('admin/users/' . $userId) . $querySuffix; ?>
            <tr>
                <td class="adm-main" data-label="#"><?= $userId ?></td>
                <td data-label="Họ tên"><?= e($u['full_name'] !== null && $u['full_name'] !== '' ? $u['full_name'] : '—') ?></td>
                <td data-label="Email / SĐT"><?= e($u['email'] ?: $u['phone'] ?: '—') ?></td>
                <td data-label="Vai trò"><span class="adm-pill <?= $isAdmin ? 'adm-pill--warn' : 'adm-pill--muted' ?>"><?= $isAdmin ? 'Quản trị viên' : 'Người dùng' ?></span></td>
                <td data-label="Trạng thái"><span class="adm-pill <?= $isActive ? 'adm-pill--ok' : 'adm-pill--muted' ?>"><?= $isActive ? 'Hoạt động' : 'Đã khóa' ?></span></td>
                <td data-label="Ngày tạo"><?= e(date('d/m/Y', strtotime((string) $u['created_at']))) ?></td>
                <td class="adm-actions-cell">
                    <a class="bh-btn bh-btn--outline bh-btn--sm adm-icon" href="<?= e($detailUrl) ?>" title="Xem" aria-label="Xem"><i class="bi bi-eye"></i></a>
                    <?php if ($canToggleActive) : ?>
                        <form class="d-inline" method="post" action="<?= e(url('admin/users/' . $userId . '/toggle-active')) ?>" data-confirm="<?= $isActive ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?' ?>"><?= csrf_field() ?>
                            <?php if ($search !== '') : ?><input type="hidden" name="q" value="<?= e($search) ?>"><?php endif; ?>
                            <?php if ($role !== '') : ?><input type="hidden" name="role" value="<?= e($role) ?>"><?php endif; ?>
                            <?php if ($active !== '') : ?><input type="hidden" name="active" value="<?= e($active) ?>"><?php endif; ?>
                            <?php if ($currentPage > 1) : ?><input type="hidden" name="page" value="<?= (int) $currentPage ?>"><?php endif; ?>
                            <button class="bh-btn bh-btn--dark bh-btn--sm" type="submit"><?= $isActive ? 'Khóa' : 'Mở khóa' ?></button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($rows === []) : ?><tr><td colspan="7" class="adm-empty">Không có người dùng phù hợp.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<div class="adm-pager"><?= $paginator->render() ?></div>