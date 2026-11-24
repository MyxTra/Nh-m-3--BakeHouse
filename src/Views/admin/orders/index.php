<?php
/** @var array $rows */
/** @var \App\Core\Paginator $paginator */
/** @var string $search */
/** @var string $status */
/** @var string $from */
/** @var string $to */
/** @var string $currentListUrl */

$statusOptions = [];
foreach (\App\Enums\OrderStatus::cases() as $s) {
    $statusOptions[$s->value] = $s->label();
}
$statusOptions['in_progress'] = 'Chưa hoàn tất';

$returnParam = 'return_url=' . urlencode($currentListUrl);
$hasFilter = ($search !== '' || $status !== '' || $from !== '' || $to !== '');
?>

<form method="get" action="<?= e(url('admin/orders')) ?>" class="adm-card mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small mb-1" for="filterQ">Tìm kiếm</label>
            <input id="filterQ" type="text" class="form-control" name="q" value="<?= e($search) ?>" placeholder="Tìm theo mã đơn, tên khách, SĐT…">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1" for="filterStatus">Trạng thái</label>
            <select id="filterStatus" class="form-select" name="status">
                <option value="">Mọi trạng thái</option>
                <?php foreach ($statusOptions as $val => $label) : ?>
                    <option value="<?= e($val) ?>" <?= $status === $val ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-6 col-md-2">
            <label class="form-label small mb-1" for="filterFrom">Từ ngày</label>
            <input id="filterFrom" type="date" class="form-control" name="from" value="<?= e($from) ?>">
        </div>
        <div class="col-sm-6 col-md-2">
            <label class="form-label small mb-1" for="filterTo">Đến ngày</label>
            <input id="filterTo" type="date" class="form-control" name="to" value="<?= e($to) ?>">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="bh-btn bh-btn--dark flex-fill"><i class="bi bi-funnel"></i> Lọc</button>
            <?php if ($hasFilter) : ?>
                <a href="<?= e(url('admin/orders')) ?>" class="bh-btn bh-btn--outline" title="Xóa lọc">Xóa lọc</a>
            <?php endif; ?>
        </div>
    </div>
</form>

<div class="adm-card adm-card--flush">
    <table class="table adm-table adm-table--stack">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th class="adm-num">Tổng đơn</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $order) :
            $baseOrderUrl = url('admin/orders/' . (int) $order['id']);
            $sep = str_contains($baseOrderUrl, '?') ? '&' : '?';
            $detailUrl = $baseOrderUrl . $sep . $returnParam; ?>
            <tr>
                <td class="adm-main"><a class="adm-name" href="<?= e($detailUrl) ?>"><?= e($order['order_code']) ?></a></td>
                <td data-label="Khách hàng"><?= e($order['recipient_name']) ?><span class="adm-sub"><?= e($order['shipping_phone']) ?></span></td>
                <td data-label="Ngày đặt"><?= e(date('d/m/Y H:i', strtotime($order['created_at']))) ?></td>
                <td class="adm-num" data-label="Tổng đơn"><strong><?= e(format_vnd((int) $order['total_amount'])) ?></strong></td>
                <td data-label="Thanh toán"><?= $order['payment_method'] === 'vietqr' ? 'VIETQR' : 'COD' ?>
                    · <?= $order['payment_status'] === 'paid' ? '<span class="badge bg-success">Đã thu</span>' : '<span class="badge bg-secondary">Chưa thu</span>' ?></td>
                <td data-label="Trạng thái"><?= status_badge($order['status']) ?></td>
                <td class="adm-actions-cell">
                    <a class="bh-btn bh-btn--outline bh-btn--sm" href="<?= e($detailUrl) ?>">Chi tiết</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($rows === []) : ?><tr><td colspan="7" class="adm-empty">Không có đơn hàng phù hợp.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<div class="adm-pager"><?= $paginator->render() ?></div>