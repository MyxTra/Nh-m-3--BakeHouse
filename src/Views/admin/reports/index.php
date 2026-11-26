<?php
/** @var string $search */ /** @var ?string $from */ /** @var ?string $to */
/** @var array{revenue: int, orders: int, delivered_orders: int} $summary */ /** @var array $rows */
$hasFilter = $search !== '' || $from !== null || $to !== null;
$deliveredCount = (int) ($summary['delivered_orders'] ?? $summary['orders'] ?? count($rows));
$kpis = [
    ['Đơn hoàn tất', number_format($deliveredCount, 0, ',', '.'), 'bi-bag-check'],
    ['Tổng doanh thu', format_vnd((int) ($summary['revenue'] ?? 0)), 'bi-cash-coin'],
];
$periodLabel = $from !== null || $to !== null
    ? ($from ? date('d/m/Y', strtotime($from)) : '…') . ' – ' . ($to ? date('d/m/Y', strtotime($to)) : '…')
    : 'Toàn bộ thời gian';
$preparedBy = trim((string) (auth_user()['full_name'] ?? '')) ?: 'Quản trị viên';

$rawUri = (string) ($_SERVER['REQUEST_URI'] ?? '');
$currentReportUrl = ($rawUri !== '' && str_starts_with($rawUri, '/') && !str_starts_with($rawUri, '//'))
    ? $rawUri
    : url('admin/report');
$returnQuery = 'return_url=' . urlencode($currentReportUrl);
?>
<!-- Chỉ hiện khi in (window.print()): bản báo cáo trình bày như văn bản hành chính, độc lập với giao diện màn hình bên dưới. -->
<div class="adm-print-doc">
    <div class="adm-print-doc__head">
        <div>
            <strong>BAKEHOUSE</strong><br>
            Địa chỉ: 12 Nguyễn Huệ, Quận 1, TP.HCM<br>
            Hotline: 0900 000 001 · Email: hello@bakehouse.vn
        </div>
        <div class="text-end">
            Mẫu số: BCDT-01<br>
            Ngày in: <?= e(date('d/m/Y - H:i')) ?>
        </div>
    </div>
    <p class="adm-print-doc__nation">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM<br><em>Độc lập - Tự do - Hạnh phúc</em></p>
    <h1 class="adm-print-doc__title">BÁO CÁO DOANH THU</h1>
    <div class="adm-print-doc__meta">
        <span>Ngày lập báo cáo: <?= e(date('d/m/Y')) ?></span>
        <span>Người lập: <?= e($preparedBy) ?></span>
    </div>
    <p class="adm-print-doc__summary">
        Kỳ báo cáo: <?= e($periodLabel) ?><?= $search !== '' ? ' · Từ khóa: ' . e($search) : '' ?>
        &nbsp;|&nbsp; Đơn hoàn tất: <?= $deliveredCount ?>
    </p>
    <table class="adm-print-doc__table">
        <thead><tr><th>STT</th><th>Mã đơn</th><th>Ngày đặt</th><th>Khách hàng</th><th>Số điện thoại</th><th>Trạng thái</th><th>Thành tiền</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $i => $order) :
            $statusEnum = \App\Enums\OrderStatus::tryFrom((string) ($order['status'] ?? 'delivered'));
            $statusText = $statusEnum !== null ? $statusEnum->label() : 'Đã giao'; ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($order['order_code']) ?></td>
                <td><?= e(date('d/m/Y', strtotime($order['created_at']))) ?></td>
                <td><?= e($order['recipient_name']) ?></td>
                <td><?= e($order['shipping_phone']) ?></td>
                <td><?= e($statusText) ?></td>
                <td><?= e(format_vnd((int) $order['total_amount'])) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($rows === []) : ?><tr><td colspan="7" style="text-align:center">Không có đơn hoàn tất phù hợp.</td></tr><?php endif; ?>
        </tbody>
        <tbody style="break-inside: avoid; page-break-inside: avoid;">
            <tr><td colspan="6"><strong>TỔNG CỘNG</strong></td><td><strong><?= e(format_vnd((int) ($summary['revenue'] ?? 0))) ?></strong></td></tr>
        </tbody>
    </table>
    <div class="adm-print-doc__sign">
        <div>Ngày ….. tháng ….. năm …..</div>
        <div><strong>Chủ cửa hàng</strong><br>(Ký, đóng dấu)</div>
    </div>
</div>

<form method="get" class="adm-toolbar">
    <div class="adm-search"><i class="bi bi-search" aria-hidden="true"></i><input class="form-control" type="search" name="q" value="<?= e($search) ?>" placeholder="Tìm theo mã đơn, tên, SĐT…" aria-label="Tìm kiếm"></div>
    <div class="adm-daterange">
        <input class="form-control" type="date" name="from" value="<?= e($from ?? '') ?>" aria-label="Từ ngày">
        <span class="bh-muted">–</span>
        <input class="form-control" type="date" name="to" value="<?= e($to ?? '') ?>" aria-label="Đến ngày">
    </div>
    <button class="bh-btn bh-btn--dark bh-btn--sm" type="submit"><i class="bi bi-search"></i> Lọc</button>
    <?php if ($hasFilter) : ?><a class="bh-btn bh-btn--outline bh-btn--sm adm-clear" href="<?= e(url('admin/report')) ?>"><i class="bi bi-x-circle"></i> Xóa lọc</a><?php endif; ?>
    <div class="adm-end"><button type="button" class="bh-btn bh-btn--primary bh-btn--sm" onclick="window.print()"><i class="bi bi-printer"></i> In báo cáo</button></div>
</form>

<div class="adm-kpis">
    <?php foreach ($kpis as [$label, $value, $icon]) : ?>
        <div class="adm-kpi">
            <div><div class="adm-kpi__label"><?= e($label) ?></div><div class="adm-kpi__value"><?= $value ?></div></div>
            <span class="adm-kpi__icon"><i class="bi <?= e($icon) ?>"></i></span>
        </div>
    <?php endforeach; ?>
</div>

<div class="adm-card adm-card--flush">
    <table class="table adm-table adm-table--stack">
        <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Số điện thoại</th><th>Ngày đặt</th><th class="adm-num">Tổng tiền</th><th>Trạng thái</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $order) :
            $baseOrderUrl = url('admin/orders/' . (int) $order['id']);
            $sep = str_contains($baseOrderUrl, '?') ? '&' : '?';
            $orderUrl = $baseOrderUrl . $sep . $returnQuery; ?>
            <tr>
                <td class="adm-main"><a class="adm-name" href="<?= e($orderUrl) ?>"><?= e($order['order_code']) ?></a></td>
                <td data-label="Khách hàng"><?= e($order['recipient_name']) ?></td>
                <td data-label="Số điện thoại"><?= e($order['shipping_phone']) ?></td>
                <td data-label="Ngày đặt"><?= e(date('d/m/Y H:i', strtotime($order['created_at']))) ?></td>
                <td class="adm-num" data-label="Tổng tiền"><?= e(format_vnd((int) $order['total_amount'])) ?></td>
                <td data-label="Trạng thái"><?= status_badge($order['status'] ?? 'delivered') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($rows === []) : ?><tr><td colspan="6" class="adm-empty">Không có đơn hoàn tất phù hợp.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>