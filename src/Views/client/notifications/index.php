<?php /** @var array $rows */ /** @var \App\Core\Paginator $paginator */
partial('hero', ['heroTitle' => 'Thông báo', 'crumbs' => [['Trang chủ', url('')], ['Thông báo', null]]]); ?>
<div class="container py-5">
<?php if ($rows === []) : ?>
    <div class="bh-panel text-center py-5"><p class="mb-0">Bạn chưa có thông báo nào.</p></div>
<?php else : ?>
    <?php foreach ($rows as $n) :
        $rawLink = trim((string) ($n['link'] ?? ''));
        $tag = $rawLink !== '' ? 'a' : 'div';
        $resolvedLink = $rawLink === '' ? '' : (preg_match('#^https?://#i', $rawLink) ? $rawLink : url(ltrim($rawLink, '/')));
        $href = $resolvedLink !== '' ? ' href="' . e($resolvedLink) . '"' : ''; ?>
        <<?= $tag ?> class="bh-panel bh-notif mb-3"<?= $href ?>>
            <span class="bh-notif__icon"><i class="bi <?= e(notification_icon((string) $n['type'])) ?>"></i></span>
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex justify-content-between gap-2 flex-wrap">
                    <strong><?= e($n['title']) ?></strong>
                    <small class="bh-muted"><?= e(time_ago((string) $n['created_at'])) ?></small>
                </div>
                <?php if ($n['message'] !== null && $n['message'] !== '') : ?>
                    <p class="mb-0 mt-1 small bh-muted"><?= e($n['message']) ?></p>
                <?php endif; ?>
            </div>
        </<?= $tag ?>>
    <?php endforeach; ?>
    <div class="mt-4"><?= $paginator->render() ?></div>
<?php endif; ?>
</div>