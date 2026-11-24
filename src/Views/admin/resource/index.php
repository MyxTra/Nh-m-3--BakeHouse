<?php /** @var array $config */ /** @var array $rows */ /** @var string $search */
$base = url('admin/' . ltrim((string) $config['route'], '/'));
$noun = mb_strtolower((string) $config['title'], 'UTF-8');
partial('admin-filter', ['search' => $search, 'placeholder' => 'Tìm ' . $noun . '…', 'createUrl' => $base . '/create', 'createLabel' => 'Thêm ' . $noun]); ?>
<div class="adm-card adm-card--flush">
    <table class="table adm-table adm-table--stack">
        <thead><tr><?php foreach ($config['columns'] as $col) : ?><th<?= $col['type'] === 'number' ? ' class="adm-num"' : '' ?>><?= e($col['label']) ?></th><?php endforeach; ?><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $row) :
            $used = (int) ($row['product_count'] ?? 0);
            $blocked = $used > 0 && !empty($config['block_delete_when_used']);
            $rowId = (int) $row['id']; ?>
            <tr>
                <?php foreach ($config['columns'] as $i => $col) :
                    $value = $row[$col['key']] ?? null;
                    $classes = [];
                    if ($i === 0) {
                        $classes[] = 'adm-main';
                    }
                    if ($col['type'] === 'number') {
                        $classes[] = 'adm-num';
                    }
                    $classAttr = $classes !== [] ? ' class="' . e(implode(' ', $classes)) . '"' : '';
                    $labelAttr = $i === 0 ? '' : ' data-label="' . e($col['label']) . '"'; ?>
                    <td<?= $classAttr . $labelAttr ?>>
                        <?php if ($col['type'] === 'bool') : ?><?= $value ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Đang ẩn</span>' ?>
                        <?php elseif ($col['type'] === 'image') : ?><?php if ($value) : ?><img class="bh-thumb" style="width:44px;height:44px" src="<?= e(img_url((string) $value)) ?>" alt=""><?php else : ?><span class="bh-muted">—</span><?php endif; ?>
                        <?php elseif ($col['type'] === 'number') : ?><?= (int) $value ?>
                        <?php elseif ($i === 0) : ?><a class="adm-name" href="<?= e($base . '/' . $rowId . '/edit') ?>"><?= e((string) $value) ?></a>
                        <?php else : ?><?= e((string) $value) ?><?php endif; ?></td>
                <?php endforeach; ?>
                <td class="adm-actions-cell">
                    <a class="bh-btn bh-btn--outline bh-btn--sm adm-icon" href="<?= e($base . '/' . $rowId . '/edit') ?>" title="Sửa" aria-label="Sửa"><i class="bi bi-pencil"></i></a>
                    <form class="d-inline" method="post" action="<?= e($base . '/' . $rowId . '/delete') ?>" data-confirm="<?= e($used > 0 && empty($config['block_delete_when_used']) ? "Xóa mục này? {$used} sản phẩm đang dùng sẽ chuyển thành \"Không thương hiệu\"." : 'Bạn có chắc muốn xóa mục này?') ?>"><?= csrf_field() ?>
                        <button class="bh-btn bh-btn--danger bh-btn--sm adm-icon" type="submit" title="<?= $blocked ? 'Đang có sản phẩm, không thể xóa' : 'Xóa' ?>" aria-label="Xóa"<?= $blocked ? ' disabled' : '' ?>><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($rows === []) : ?><tr><td colspan="<?= count($config['columns']) + 1 ?>" class="adm-empty">Chưa có dữ liệu.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>