<?php
/** @var array $order */
/** @var array $items */
/** @var array $histories */
/** @var string $returnUrl */

$currentStatus = \App\Enums\OrderStatus::from($order['status']);
$next = $currentStatus->next();
$orderId = (int) $order['id'];

$rawReturn = trim((string) ($returnUrl ?? ''));
$safeReturn = ($rawReturn !== '' && str_starts_with($rawReturn, '/') && !str_starts_with($rawReturn, '//'))
    ? $rawReturn
    : '';
$backUrl = $safeReturn !== '' ? $safeReturn : url('admin/orders');
$paymentBlocked = $order['payment_method'] === 'vietqr' && $order['payment_status'] === 'unpaid';
?>
<div class="row g-3">
    <div class="col-lg-8">
        <section class="adm-card adm-card--flush mb-3">
            <div class="adm-card__head px-3 pt-3 mb-0"><h2>Sản phẩm trong đơn</h2><?= status_badge($order['status']) ?></div>
            <table class="table adm-table adm-table--stack mt-2">
                <thead><tr><th>Sản phẩm</th><th class="adm-num">Đơn giá</th><th class="adm-num">SL</th></tr></thead>
                <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td class="adm-main"><div class="adm-cell"><img class="adm-thumb" src="<?= e(img_url($item['image_path'] ?? null)) ?>" alt="" loading="lazy"><span><?= e($item['product_name']) ?></span></div></td>
                        <td class="adm-num" data-label="Đơn giá"><?= e(format_vnd((int) $item['unit_price'])) ?></td>
                        <td class="adm-num" data-label="SL"><?= (int) $item['quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="px-3 pb-3 pt-2 border-top" style="border-color:var(--bh-color-border)!important">
                <div class="d-flex justify-content-between"><span class="bh-muted">Phí vận chuyển</span><span><?= e(format_vnd((int) $order['shipping_fee'])) ?></span></div>
                <div class="d-flex justify-content-between fs-5 mt-1"><strong>Tổng đơn</strong><strong class="bh-price"><?= e(format_vnd((int) $order['total_amount'])) ?></strong></div>
            </div>
        </section>
        <section class="adm-card">
            <div class="adm-card__head"><h2>Lịch sử xử lý</h2></div>
            <ul class="list-unstyled mb-0 small">
            <?php foreach ($histories as $h) : ?>
                <li class="mb-2"><i class="bi bi-clock-history me-1 bh-muted"></i><?= e(date('d/m/Y H:i', strtotime($h['created_at']))) ?> — <?= $h['from_status'] ? status_badge($h['from_status']) . ' → ' : 'Tạo đơn: ' ?><?= status_badge($h['to_status']) ?> <span class="bh-muted">bởi <?= e($h['changed_by_name'] ?? 'hệ thống') ?></span></li>
            <?php endforeach; ?></ul>
        </section>
    </div>
    <div class="col-lg-4">
        <section class="adm-card mb-3">
            <div class="adm-card__head"><h2>Khách hàng</h2></div>
            <div class="fw-semibold"><?= e($order['recipient_name']) ?></div>
            <div><i class="bi bi-telephone bh-muted"></i> <?= e($order['shipping_phone']) ?></div>
            <div><i class="bi bi-geo-alt bh-muted"></i> <?= e($order['shipping_address']) ?></div>
            <?php if ($order['note']) : ?><div class="mt-2 p-2 rounded" style="background:#faf6ef"><i class="bi bi-chat-left-text bh-muted"></i> <?= e($order['note']) ?></div><?php endif; ?>
            <div class="bh-muted small mt-2">Tài khoản: <?= e($order['account_email'] ?: $order['account_phone'] ?: '—') ?></div>
        </section>
        <section class="adm-card mb-3">
            <div class="adm-card__head"><h2>Thanh toán</h2></div>
            <div><?= e(\App\Enums\PaymentMethod::from($order['payment_method'])->label()) ?></div>
            <div class="mb-2"><?= $order['payment_status'] === 'paid' ? '<span class="badge bg-success">Đã thanh toán</span>' : '<span class="badge bg-secondary">Chưa thanh toán</span>' ?></div>
            <?php if ($order['payment_method'] === 'vietqr' && $order['payment_status'] === 'unpaid') : ?>
                <form method="post" action="<?= e(url('admin/orders/' . $orderId . '/confirm-payment')) ?>" data-confirm="Xác nhận đã nhận đủ tiền chuyển khoản cho đơn này?"><?= csrf_field() ?>
                    <input type="hidden" name="return_url" value="<?= e($backUrl) ?>">
                    <button class="bh-btn bh-btn--primary w-100" type="submit"><i class="bi bi-cash-coin"></i> Xác nhận đã nhận tiền</button></form>
            <?php endif; ?>
        </section>
        <section class="adm-card mb-3">
            <div class="adm-card__head"><h2>Cập nhật trạng thái</h2></div>
            <?php if ($next === null) : ?>
                <p class="bh-muted mb-0"><i class="bi bi-check-circle text-success"></i> Đơn hàng đã hoàn tất.</p>
            <?php elseif ($paymentBlocked) : ?>
                <p class="mb-0 text-danger"><i class="bi bi-exclamation-triangle"></i> Đơn thanh toán VietQR chưa được xác nhận nhận tiền. Vui lòng xác nhận ở mục Thanh toán trước khi cập nhật trạng thái.</p>
            <?php else : ?>
                <form method="post" action="<?= e(url('admin/orders/' . $orderId . '/status')) ?>"><?= csrf_field() ?>
                    <input type="hidden" name="return_url" value="<?= e($backUrl) ?>">
                    <select class="form-select mb-2" name="status" aria-label="Trạng thái kế tiếp">
                        <option value="<?= e($next->value) ?>"><?= e($next->label()) ?></option>
                    </select>
                    <button class="bh-btn bh-btn--dark w-100" type="submit">Cập nhật</button>
                    <div class="form-text">Chỉ được chuyển sang trạng thái kế tiếp liền kề, không được nhảy cóc hoặc quay lại.</div>
                </form>
            <?php endif; ?>
        </section>
        <a class="bh-btn bh-btn--outline" href="<?= e($backUrl) ?>">Quay lại</a>
    </div>
</div>