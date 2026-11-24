<?php
/** @var array $summary */ /** @var array $contactCounts */ /** @var array $reviewCounts */ /** @var array $productCounts */
/** @var array $monthlyChart */ /** @var array $dailyChart */ /** @var array $topSelling */ /** @var array $mostReviewed */
/** @var array $recentOrders */ /** @var array $recentActivity */
$kpis = [
    ['Tổng đơn hàng', number_format((int) $summary['orders'], 0, ',', '.'), url('admin/orders'), 'bi-journal-text'],
    ['Đơn hoàn tất', number_format((int) $summary['delivered_orders'], 0, ',', '.'), url('admin/orders') . '?status=delivered', 'bi-bag-check'],
    ['Đơn chưa hoàn tất', number_format((int) $summary['orders'] - (int) $summary['delivered_orders'], 0, ',', '.'), url('admin/orders') . '?status=in_progress', 'bi-hourglass-split'],
    ['Nguyên vật liệu đã bán', number_format((int) $summary['items_sold'], 0, ',', '.'), url('admin/products'), 'bi-box-seam'],
    ['Doanh thu', format_vnd((int) $summary['revenue']), url('admin/report'), 'bi-cash-coin'],
    ['Doanh thu trung bình / đơn', format_vnd((int) $summary['average']), url('admin/report'), 'bi-graph-up'],
    ['Đánh giá chưa phản hồi', number_format((int) ($reviewCounts['unanswered'] ?? 0), 0, ',', '.'), url('admin/reviews') . '?view=unanswered', 'bi-chat-left-dots'],
    ['Liên hệ chưa phản hồi', number_format((int) ($contactCounts['new'] ?? 0), 0, ',', '.'), url('admin/contacts') . '?status=new', 'bi-envelope-exclamation'],
    ['Nguyên vật liệu cần nhập thêm', number_format((int) ($productCounts['restock'] ?? 0), 0, ',', '.'), url('admin/products') . '?view=restock', 'bi-exclamation-triangle'],
];
$activityLabels = ['pending' => 'Chờ xử lý', 'processing' => 'Đã xác nhận', 'shipped' => 'Đang giao', 'delivered' => 'Hoàn tất'];
$jsonFlags = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
?>
<div class="adm-kpis">
    <?php foreach ($kpis as [$label, $value, $href, $icon]) : ?>
        <a class="adm-kpi" href="<?= e($href) ?>">
            <div><div class="adm-kpi__label"><?= e($label) ?></div><div class="adm-kpi__value"><?= e($value) ?></div></div>
            <span class="adm-kpi__icon"><i class="bi <?= e($icon) ?>"></i></span>
        </a>
    <?php endforeach; ?>
</div>

<div class="adm-grid">
    <section class="adm-card">
        <div class="adm-card__head"><h2>Doanh thu theo tháng</h2></div>
        <div class="adm-chart"><canvas id="chartMonthly" aria-label="Biểu đồ doanh thu theo tháng" role="img"></canvas></div>
    </section>
    <section class="adm-card">
        <div class="adm-card__head"><h2>Doanh thu 30 ngày gần nhất</h2></div>
        <div class="adm-chart"><canvas id="chartDaily" aria-label="Biểu đồ doanh thu 30 ngày gần nhất" role="img"></canvas></div>
    </section>
</div>

<div class="adm-grid">
    <section class="adm-card">
        <div class="adm-card__head"><h2>Nguyên vật liệu bán chạy nhất</h2></div>
        <?php if ($topSelling === []) : ?><p class="bh-muted mb-0">Chưa có đơn hoàn tất.</p>
        <?php else : ?>
            <ol class="adm-rank">
                <?php foreach ($topSelling as $i => $row) : ?>
                    <li><span class="adm-rank__n"><?= $i + 1 ?></span><span class="adm-rank__name"><?= e($row['product_name']) ?></span>
                        <span class="adm-pill adm-pill--warn"><?= (int) $row['qty'] ?> đã bán</span></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </section>
    <section class="adm-card">
        <div class="adm-card__head"><h2>Nguyên vật liệu được đánh giá nhiều nhất</h2></div>
        <?php if ($mostReviewed === []) : ?><p class="bh-muted mb-0">Chưa có đánh giá nào.</p>
        <?php else : ?>
            <ol class="adm-rank">
                <?php foreach ($mostReviewed as $i => $row) : ?>
                    <li><span class="adm-rank__n"><?= $i + 1 ?></span><span class="adm-rank__name"><?= e($row['name']) ?></span>
                        <span class="adm-pill adm-pill--warn"><?= (int) $row['rating_count'] ?> đánh giá · <?= number_format((float) $row['rating_avg'], 1) ?>★</span></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </section>
</div>

<div class="adm-grid">
    <section class="adm-card adm-card--flush">
        <div class="adm-card__head px-3 pt-3 mb-0"><h2>Đơn hàng gần đây</h2><a href="<?= e(url('admin/orders')) ?>">Xem tất cả</a></div>
        <table class="table adm-table mt-2 d-none d-md-table">
            <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th class="adm-num">Tổng đơn</th><th>Trạng thái</th></tr></thead>
            <tbody>
            <?php foreach ($recentOrders as $order) :
                $orderUrl = url('admin/orders/' . (int) $order['id']); ?>
                <tr>
                    <td><a class="adm-name" href="<?= e($orderUrl) ?>"><?= e($order['order_code']) ?></a></td>
                    <td><?= e($order['recipient_name']) ?></td>
                    <td class="adm-num"><?= e(format_vnd((int) $order['total_amount'])) ?></td>
                    <td><?= status_badge($order['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <ul class="adm-olist d-md-none">
            <?php foreach ($recentOrders as $order) :
                $orderUrl = url('admin/orders/' . (int) $order['id']); ?>
                <li><a href="<?= e($orderUrl) ?>">
                    <span><strong><?= e($order['order_code']) ?></strong> · <?= e($order['recipient_name']) ?></span><?= status_badge($order['status']) ?>
                    <strong><?= e(format_vnd((int) $order['total_amount'])) ?></strong><span></span>
                </a></li>
            <?php endforeach; ?>
        </ul>
        <?php if ($recentOrders === []) : ?><div class="adm-empty">Chưa có đơn hàng.</div><?php endif; ?>
    </section>
    <section class="adm-card">
        <div class="adm-card__head"><h2>Hoạt động gần đây</h2></div>
        <?php if ($recentActivity === []) : ?><p class="bh-muted mb-0">Chưa có hoạt động nào.</p>
        <?php else : ?>
            <ul class="adm-activity">
                <?php foreach ($recentActivity as $a) : ?>
                    <li>
                        <div>Đơn <strong><?= e($a['order_code']) ?></strong> chuyển sang <?= e($activityLabels[$a['to_status']] ?? $a['to_status']) ?></div>
                        <small><?= e(date('d/m/Y H:i', strtotime($a['created_at']))) ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
  if (typeof Chart === 'undefined') { return; }
  var css = getComputedStyle(document.documentElement);
  var fmt = function (v) { return new Intl.NumberFormat('vi-VN').format(v) + ' ₫'; };
  var barOptions = function () {
    return {
      maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return fmt(c.parsed.y); } } } },
      scales: {
        x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 10 } },
        y: { beginAtZero: true, ticks: { callback: function (v) { return v >= 1e6 ? (v / 1e6) + ' tr' : (v >= 1e3 ? (v / 1e3) + 'k' : v); } } }
      }
    };
  };

  var monthlyEl = document.getElementById('chartMonthly');
  if (monthlyEl) {
    var monthly = <?= json_encode($monthlyChart, $jsonFlags) ?>;
    new Chart(monthlyEl, {
      type: 'bar',
      data: {
        labels: Object.keys(monthly).map(function (k) { var p = k.split('-'); return p[1] + '/' + p[0]; }),
        datasets: [{ data: Object.values(monthly), backgroundColor: css.getPropertyValue('--bh-color-caramel').trim(), borderRadius: 4, maxBarThickness: 28 }]
      },
      options: barOptions()
    });
  }

  var dailyEl = document.getElementById('chartDaily');
  if (dailyEl) {
    var daily = <?= json_encode($dailyChart, $jsonFlags) ?>;
    new Chart(dailyEl, {
      type: 'line',
      data: {
        labels: Object.keys(daily).map(function (k) { var p = k.split('-'); return p[1] + '-' + p[2]; }),
        datasets: [{
          data: Object.values(daily), borderColor: css.getPropertyValue('--bh-color-rust').trim(),
          backgroundColor: 'rgba(168, 94, 48, .12)', fill: true, tension: .3, pointRadius: 2, maxBarThickness: 28
        }]
      },
      options: barOptions()
    });
  }
})();
</script>