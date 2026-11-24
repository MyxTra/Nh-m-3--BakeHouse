<?php
/** @var array $products */ /** @var array $categories */ /** @var \App\Core\Paginator $paginator */
/** @var array $filters */ /** @var string $sort */ /** @var int $sliderMax */
$sortOptions = ['newest' => 'Mới nhất', 'price_asc' => 'Giá tăng dần', 'price_desc' => 'Giá giảm dần', 'best_selling' => 'Bán chạy', 'top_rated' => 'Đánh giá cao'];
$currentMin = max(0, (int) ($filters['min_price'] ?? 0));
$currentMax = (int) ($filters['max_price'] ?? 0) > 0 ? (int) $filters['max_price'] : $sliderMax;
$productsUrl = url('products');
$hasActiveFilter = $filters['q'] !== '' || $filters['category'] !== '' || $currentMin > 0 || ((int) ($filters['max_price'] ?? 0)) > 0;
partial('hero', ['heroTitle' => 'Sản Phẩm', 'crumbs' => [['Trang chủ', url('')], ['Sản phẩm', null]]]);
?>
<div class="container py-5">
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-3">
        <form action="<?= e($productsUrl) ?>" method="get" class="bh-product-search m-0">
            <?php if ($filters['category'] !== '') : ?><input type="hidden" name="category" value="<?= e($filters['category']) ?>"><?php endif; ?>
            <input type="hidden" name="sort" value="<?= e($sort) ?>">
            <?php if ($currentMin > 0) : ?><input type="hidden" name="min_price" value="<?= $currentMin ?>"><?php endif; ?>
            <?php if ((int) ($filters['max_price'] ?? 0) > 0) : ?><input type="hidden" name="max_price" value="<?= (int) $filters['max_price'] ?>"><?php endif; ?>
            <div class="input-group">
                <input class="form-control" type="search" name="q" placeholder="Tên hoặc thương hiệu" value="<?= e($filters['q']) ?>">
                <button class="bh-btn bh-btn--dark" type="submit" aria-label="Tìm"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <?php if ($hasActiveFilter) : ?>
            <a class="bh-btn bh-btn--outline text-nowrap" href="<?= e($productsUrl) ?>"><i class="bi bi-x-lg"></i> Xóa lọc</a>
        <?php endif; ?>
    </div>
    <div class="bh-toolbar mb-4">
        <span class="bh-muted">Hiển thị <?= $paginator->getFirstItem() ?>–<?= $paginator->getLastItem() ?> của <?= $paginator->getTotalItems() ?> kết quả</span>
        <form method="get" action="<?= e($productsUrl) ?>" class="d-flex align-items-center gap-2">
            <?php foreach (['q' => $filters['q'], 'category' => $filters['category']] as $key => $value) : ?>
                <?php if ($value !== '') : ?><input type="hidden" name="<?= e($key) ?>" value="<?= e($value) ?>"><?php endif; ?>
            <?php endforeach; ?>
            <?php if ($currentMin > 0) : ?><input type="hidden" name="min_price" value="<?= $currentMin ?>"><?php endif; ?>
            <?php if ((int) ($filters['max_price'] ?? 0) > 0) : ?><input type="hidden" name="max_price" value="<?= (int) $filters['max_price'] ?>"><?php endif; ?>
            <label class="bh-muted" for="bhSort">Sắp xếp theo:</label>
            <select class="form-select" id="bhSort" name="sort" data-autosubmit>
                <?php foreach ($sortOptions as $value => $label) : ?>
                    <option value="<?= e($value) ?>"<?= $sort === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="d-flex gap-2 mb-4 d-lg-none">
        <button class="bh-btn bh-btn--outline flex-grow-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#bhFilters"><i class="bi bi-funnel"></i> Bộ lọc</button>
    </div>
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="bhFilters">
                <div class="offcanvas-header d-lg-none"><h5>Bộ lọc</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#bhFilters"></button></div>
                <div class="offcanvas-body d-block p-3 p-lg-0">
                    <h3 class="bh-sidebar__title">Danh mục sản phẩm</h3>
                    <ul class="bh-cat-list">
                        <li><a href="<?= e(query_url([], ['category', 'page'])) ?>" class="<?= $filters['category'] === '' ? 'is-active' : '' ?>"><i class="bi bi-grid"></i> Tất cả sản phẩm</a></li>
                        <?php foreach ($categories as $category) : ?>
                            <li><a href="<?= e(query_url(['category' => $category['slug']], ['page'])) ?>" class="<?= $filters['category'] === $category['slug'] ? 'is-active' : '' ?>"><i class="bi bi-chevron-right"></i> <?= e($category['name']) ?> <small class="bh-muted">(<?= (int) $category['product_count'] ?>)</small></a></li>
                        <?php endforeach; ?>
                    </ul>

                    <h3 class="bh-sidebar__title">Lọc theo giá</h3>
                    <form action="<?= e($productsUrl) ?>" method="get" id="bhPriceFilterForm">
                        <?php foreach (['q' => $filters['q'], 'category' => $filters['category'], 'sort' => $sort] as $key => $value) : ?>
                            <?php if ($value !== '') : ?><input type="hidden" name="<?= e($key) ?>" value="<?= e($value) ?>"><?php endif; ?>
                        <?php endforeach; ?>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small bh-muted mb-1" for="bhMinPriceInput">Từ (₫)</label>
                                <input class="form-control form-control-sm" id="bhMinPriceInput" type="number" name="min_price" min="0" step="1" value="<?= $currentMin ?>" placeholder="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label small bh-muted mb-1" for="bhMaxPriceInput">Đến (₫)</label>
                                <input class="form-control form-control-sm" id="bhMaxPriceInput" type="number" name="max_price" min="0" step="1" value="<?= $currentMax ?>" placeholder="<?= $sliderMax ?>">
                            </div>
                        </div>
                        <input class="bh-range" id="bhPriceRange" type="range" min="0" max="<?= $sliderMax ?>" step="10000" value="<?= min($currentMax, $sliderMax) ?>">
                        <div class="d-flex justify-content-between small bh-muted mt-1">
                            <span>Từ: <strong id="bhMinPriceOutput"><?= e(format_vnd($currentMin)) ?></strong></span>
                            <span>Đến: <strong id="bhPriceOutput"><?= e(format_vnd($currentMax)) ?></strong></span>
                        </div>
                        <button class="bh-btn bh-btn--primary w-100 mt-3" type="submit">Áp dụng</button>
                    </form>
                </div>
            </div>
        </aside>

        <section class="col-lg-9">
            <?php if ($products === []) : ?>
                <div class="bh-panel text-center py-5"><i class="bi bi-search fs-1 bh-muted"></i><p class="mt-3 mb-0">Không tìm thấy sản phẩm phù hợp.</p></div>
            <?php else : ?>
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    <?php foreach ($products as $product) : ?>
                        <div class="col"><?php partial('product-card', ['product' => $product]); ?></div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-5"><?= $paginator->render() ?></div>
            <?php endif; ?>
        </section>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const minInput = document.getElementById('bhMinPriceInput');
    const maxInput = document.getElementById('bhMaxPriceInput');
    const range = document.getElementById('bhPriceRange');
    const minOut = document.getElementById('bhMinPriceOutput');
    const maxOut = document.getElementById('bhPriceOutput');
    const fmt = (val) => (Number(val) || 0).toLocaleString('vi-VN') + ' ₫';

    function syncLabels() {
        if (minInput && minOut) minOut.textContent = fmt(minInput.value);
        if (maxInput && maxOut) maxOut.textContent = fmt(maxInput.value);
    }

    if (range && maxInput) {
        range.addEventListener('input', function () {
            maxInput.value = range.value;
            syncLabels();
        });
        maxInput.addEventListener('input', function () {
            const num = Number(maxInput.value) || 0;
            const sliderMaxVal = Number(range.max) || num;
            range.value = String(Math.min(Math.max(0, num), sliderMaxVal));
            syncLabels();
        });
    }
    if (minInput) {
        minInput.addEventListener('input', syncLabels);
    }
    syncLabels();
});
</script>