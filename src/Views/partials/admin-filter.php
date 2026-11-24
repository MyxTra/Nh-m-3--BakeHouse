<?php
/** @var string $search */ /** @var string $placeholder */
/** @var array $selects  [name => [label, options(value=>label), current]] */
/** @var array $hidden   [name => value] kept when filtering (e.g. the active tab) */
/** @var ?string $createUrl */ /** @var ?string $createLabel */
$active = $search !== '';
$keep = array_filter($hidden ?? [], static fn ($v): bool => (string) $v !== '');
$baseUrl = url(ltrim(current_path(), '/'));
$clearUrl = $baseUrl . ($keep !== [] ? '?' . http_build_query($keep) : '');
foreach (($selects ?? []) as [, , $current]) {
    $active = $active || (string) $current !== '';
}
$resolvedCreateUrl = !empty($createUrl)
    ? (preg_match('#^https?://#i', (string) $createUrl) ? (string) $createUrl : url(ltrim((string) $createUrl, '/')))
    : '';
?>
<form method="get" class="adm-toolbar" role="search">
    <?php foreach (($hidden ?? []) as $name => $value) : if ((string) $value !== '') : ?><input type="hidden" name="<?= e((string) $name) ?>" value="<?= e((string) $value) ?>"><?php endif; endforeach; ?>
    <div class="adm-search"><i class="bi bi-search" aria-hidden="true"></i><input class="form-control" type="search" name="q" value="<?= e($search) ?>" placeholder="<?= e($placeholder ?? 'Tìm kiếm…') ?>" aria-label="<?= e($placeholder ?? 'Tìm kiếm') ?>"></div>
    <?php foreach (($selects ?? []) as $name => [$label, $options, $current]) : ?>
        <select class="form-select" name="<?= e((string) $name) ?>" aria-label="<?= e((string) $label) ?>" data-autosubmit>
            <option value=""><?= e((string) $label) ?></option>
            <?php foreach ($options as $value => $text) : ?><option value="<?= e((string) $value) ?>"<?= (string) $current === (string) $value ? ' selected' : '' ?>><?= e((string) $text) ?></option><?php endforeach; ?>
        </select>
    <?php endforeach; ?>
    <button class="bh-btn bh-btn--dark bh-btn--sm" type="submit"><i class="bi bi-search"></i> Lọc</button>
    <?php if ($active) : ?><a class="bh-btn bh-btn--outline bh-btn--sm adm-clear" href="<?= e($clearUrl) ?>"><i class="bi bi-x-circle"></i> Xóa lọc</a><?php endif; ?>
    <?php if ($resolvedCreateUrl !== '') : ?><div class="adm-end"><a class="bh-btn bh-btn--primary bh-btn--sm" href="<?= e($resolvedCreateUrl) ?>"><i class="bi bi-plus-lg"></i> <?= e($createLabel ?? 'Thêm mới') ?></a></div><?php endif; ?>
</form>