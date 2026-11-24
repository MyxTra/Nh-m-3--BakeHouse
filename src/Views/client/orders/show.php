<?php /** @var array $order */ /** @var array $items */ /** @var array $histories */ /** @var ?string $qrUrl */ /** @var array $account */
$steps = \App\Enums\OrderStatus::cases();
$currentRank = \App\Enums\OrderStatus::from($order['status'])->rank();
$stepTimes = [];
foreach ($histories as $history) { $stepTimes[$history['to_status']] = $history['created_at']; }
$orderCode = (string) $order['order_code'];
partial('hero', ['heroTitle' => 'Đơn hàng ' . $orderCode, 'crumbs' => [['Trang chủ', url('')], ['Đơn hàng', url('orders')], [$orderCode, null]]]); ?>
<div class="container py-5">
    <div class="bh-panel mb-4">
        <div class="bh-stepper">
            <?php foreach ($steps as $step) : ?>
                <div class="bh-step<?= $step->rank() <= $currentRank ? ' is-done' : '' ?>">
                    <div class="bh-step__dot"><i class="bi <?= $step->rank() <= $currentRank ? 'bi-check-lg' : 'bi-circle' ?>"></i></div>
                    <?= e($step->label()) ?>
                    <?php if (isset($stepTimes[$step->value])) : ?><small><?= e(date('d/m H:i', strtotime((string) $stepTimes[$step->value]))) ?></small><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bh-panel">
                <h5 class="mb-3">Sản phẩm</h5>
                <table class="bh-table bh-table--stack">
                    <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>SL</th><th>Thành tiền</th><th>Trạng thái</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item) : ?>
                        <tr>
                            <td data-label="Sản phẩm"><?= e($item['product_name']) ?></td>
                            <td data-label="Đơn giá"><?= e(format_vnd((int) $item['unit_price'])) ?></td>
                            <td data-label="SL"><?= (int) $item['quantity'] ?></td>
                            <td data-label="Thành tiền"><?= e(format_vnd((int) $item['unit_price'] * (int) $item['quantity'])) ?></td>
                            <td data-label="Trạng thái"><?= status_badge($item['status']) ?></td>
                            <td data-label="">
                                <?php if ($item['status'] === 'delivered' && !empty($item['product_slug'])) : ?>
                                    <a class="bh-btn bh-btn--outline bh-btn--sm" href="<?= e(url('products/' . (string) $item['product_slug'])) ?>#reviews"><i class="bi bi-star"></i> Đánh giá</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between"><span>Phí vận chuyển</span><span><?= e(format_vnd((int) $order['shipping_fee'])) ?></span></div>
                <div class="d-flex justify-content-between fs-5 mt-2"><span>Tổng cộng</span><strong class="bh-price"><?= e(format_vnd((int) $order['total_amount'])) ?></strong></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bh-panel mb-4">
                <h5>Giao hàng</h5>
                <div><?= e($order['recipient_name']) ?></div><div><?= e($order['shipping_phone']) ?></div><div><?= e($order['shipping_address']) ?></div>
                <?php if (!empty($order['note'])) : ?><div class="bh-muted mt-2">Ghi chú: <?= e($order['note']) ?></div><?php endif; ?>
                <hr><div>Thanh toán: <?= e(\App\Enums\PaymentMethod::from($order['payment_method'])->label()) ?></div>
                <div><?= $order['payment_status'] === 'paid' ? '<span class="badge bg-success">Đã thanh toán</span>' : '<span class="badge bg-secondary">Chưa thanh toán</span>' ?></div>
            </div>
            <?php if (!empty($qrUrl)) : ?>
                <div class="bh-panel text-center">
                    <h5>Quét mã để thanh toán</h5>
                    <img class="img-fluid" src="<?= e((string) $qrUrl) ?>" alt="Mã VietQR thanh toán đơn <?= e($orderCode) ?>" style="max-width:260px">
                    <div class="small mt-2">Số tiền: <strong><?= e(format_vnd((int) $order['total_amount'])) ?></strong><br>Nội dung: <strong><?= e($orderCode) ?></strong></div>
                    <div class="small bh-muted mt-1">Cửa hàng sẽ xác nhận sau khi nhận được tiền.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>