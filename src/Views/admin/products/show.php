<?php /** @var array $product */ /** @var array $images */ /** @var bool $canModify */
$allowedViews = ['active', 'restock', 'hidden'];
$backView = isset($_GET['view']) && in_array($_GET['view'], $allowedViews, true) ? (string) $_GET['view'] : '';
$backQ = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$backCategory = isset($_GET['category']) && ctype_digit((string) $_GET['category']) && (int) $_GET['category'] > 0
    ? (int) $_GET['category']
    : 0;
$backPage = isset($_GET['page']) && ctype_digit((string) $_GET['page']) && (int) $_GET['page'] > 1
    ? (int) $_GET['page']
    : 1;
$backParams = array_filter([
    'view' => $backView !== '' ? $backView : null,
    'q' => $backQ !== '' ? $backQ : null,
    'category' => $backCategory > 0 ? $backCategory : null,
    'page' => $backPage > 1 ? $backPage : null,
], static fn (mixed $val): bool => $val !== null);
$backSuffix = $backParams !== [] ? '?' . http_build_query($backParams) : '';
$editUrl = url('admin/products/' . (int) $product['id'] . '/edit') . $backSuffix;
$backUrl = url('admin/products') . $backSuffix; ?>
<div class="bh-panel">
    <div class="row g-4">
        <div class="col-md-4"><img class="img-fluid rounded-3" src="<?= e(img_url($product['image_path'])) ?>" alt="">
            <div class="d-flex gap-2 flex-wrap mt-2"><?php foreach ($images as $image) : ?><img class="bh-thumb" src="<?= e(img_url($image['image_path'])) ?>" alt=""><?php endforeach; ?></div></div>
        <div class="col-md-8">
            <h3><?= e($product['name']) ?></h3>
            <p class="bh-muted"><?= e($product['category_name']) ?> · <?= e($product['brand_name'] ?? 'Không thương hiệu') ?></p>
            <p><strong><?= e(format_vnd((int) $product['price'])) ?></strong> / <?= e($product['unit']) ?> · Tồn kho: <?= (int) $product['stock_quantity'] ?></p>
            <p><?= nl2br(e($product['description'] ?? '')) ?></p>
            <div class="mt-3">
                <?php if ($canModify) : ?><a class="bh-btn bh-btn--primary" href="<?= e($editUrl) ?>">Chỉnh sửa</a>
                <?php else : ?><span class="badge bg-secondary">Chỉ đọc — sản phẩm của quản trị viên khác</span><?php endif; ?>
                <a class="bh-btn bh-btn--outline ms-2" href="<?= e($backUrl) ?>">Quay lại</a>
            </div>
        </div>
    </div>
</div>