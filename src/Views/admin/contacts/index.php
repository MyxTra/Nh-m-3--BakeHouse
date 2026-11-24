<?php /** @var array $rows */ /** @var \App\Core\Paginator $paginator */ /** @var array $counts */ /** @var string $search */ /** @var string $status */ /** @var string $fromDate */ /** @var string $toDate */
$labels = ['new' => 'Chưa phản hồi', 'replied' => 'Đã phản hồi'];
$pills = ['new' => 'adm-pill--warn', 'replied' => 'adm-pill--ok'];

$keepParams = array_filter([
    'q' => $search !== '' ? $search : null,
    'from_date' => $fromDate !== '' ? $fromDate : null,
    'to_date' => $toDate !== '' ? $toDate : null,
], static fn ($v) => $v !== null);

$resetQuery = $status !== '' ? '?status=' . urlencode($status) : '';

partial('admin-tabs', [
    'current' => $status,
    'tabs' => ['' => 'Tất cả'] + $labels,
    'counts' => ['' => $counts['all'], 'new' => $counts['new'], 'replied' => $counts['replied']],
    'alert' => ['new' => true],
    'keep' => $keepParams,
]); ?>

<form class="adm-filter d-flex flex-wrap gap-2 align-items-center mb-3" method="get" action="<?= e(url('admin/contacts')) ?>">
    <?php if ($status !== '') : ?>
        <input type="hidden" name="status" value="<?= e($status) ?>">
    <?php endif; ?>
    <div class="flex-grow-1" style="min-width: 210px;">
        <input class="form-control" type="search" name="q" value="<?= e($search) ?>" placeholder="Tìm theo tên, email, số điện thoại, nội dung…">
    </div>
    <div class="d-flex align-items-center gap-1">
        <span class="bh-muted small">Từ</span>
        <input class="form-control" type="date" name="from_date" value="<?= e($fromDate) ?>" style="width: auto;" aria-label="Từ ngày">
    </div>
    <div class="d-flex align-items-center gap-1">
        <span class="bh-muted small">Đến</span>
        <input class="form-control" type="date" name="to_date" value="<?= e($toDate) ?>" style="width: auto;" aria-label="Đến ngày">
    </div>
    <button class="bh-btn bh-btn--primary" type="submit"><i class="bi bi-funnel"></i> Lọc</button>
    <?php if ($search !== '' || $fromDate !== '' || $toDate !== '') : ?>
        <a class="bh-btn bh-btn--outline" href="<?= e(url('admin/contacts' . $resetQuery)) ?>">Xóa lọc</a>
    <?php endif; ?>
</form>

<div class="adm-contacts">
<?php foreach ($rows as $c) :
    $isReplied = ($c['status'] === 'replied');
    $statusKey = $isReplied ? 'replied' : 'new';
    $initial = mb_strtoupper(mb_substr(trim((string) $c['full_name']), 0, 1)) ?: '?';
    $preview = mb_strimwidth(preg_replace('/\s+/u', ' ', (string) $c['message']), 0, 180, '…'); ?>
    <a class="adm-contact<?= !$isReplied ? ' is-new' : '' ?>" href="<?= e(url('admin/contacts/' . (int) $c['id'])) ?>">
        <span class="adm-avatar" aria-hidden="true"><?= e($initial) ?></span>
        <div class="adm-contact__main">
            <div class="adm-contact__top">
                <strong class="adm-contact__name"><?= e($c['full_name']) ?></strong>
                <span class="adm-pill <?= e($pills[$statusKey]) ?>"><?= e($labels[$statusKey]) ?></span>
            </div>
            <div class="adm-contact__meta">
                <?php if (!empty($c['email'])) : ?><span><i class="bi bi-envelope"></i> <?= e($c['email']) ?></span><?php endif; ?>
                <?php if (!empty($c['phone'])) : ?><span><i class="bi bi-telephone"></i> <?= e($c['phone']) ?></span><?php endif; ?>
                <span><i class="bi bi-clock"></i> <?= e(date('d/m/Y H:i', strtotime($c['created_at']))) ?></span>
            </div>
            <p class="adm-contact__text"><?= e($preview) ?></p>
        </div>
        <i class="bi bi-chevron-right adm-contact__go" aria-hidden="true"></i>
    </a>
<?php endforeach; ?>
<?php if ($rows === []) : ?><div class="adm-card adm-empty">Không có liên hệ phù hợp.</div><?php endif; ?>
</div>
<div class="adm-pager"><?= $paginator->render() ?></div>