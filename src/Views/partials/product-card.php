<?php /** @var array<string, mixed> $product */
$inStock = (int) $product['stock_quantity'] > 0;
$detailUrl = url('products/' . (string) $product['slug']); ?>
<article class="bh-card">
    <a class="bh-card__media" href="<?= e($detailUrl) ?>">
        <img loading="lazy" src="<?= e(img_url($product['image_path'])) ?>" alt="<?= e($product['name']) ?>">
        <?php if (!empty($product['is_best'])) : ?><span class="bh-badge bh-badge--best">Best Seller</span><?php endif; ?>
        <?php if (!empty($product['is_new'])) : ?><span class="bh-badge bh-badge--new">New</span><?php endif; ?>
    </a>
    <div class="bh-card__body">
        <div class="bh-card__category"><?= e($product['category_name']) ?></div>
        <h3 class="bh-card__title"><a href="<?= e($detailUrl) ?>"><?= e($product['name']) ?></a></h3>
        <div class="bh-card__meta">
            <?php if (!empty($product['brand_name'])) : ?><?= e($product['brand_name']) ?> · <?php endif; ?>
            <?= render_stars((float) $product['rating_avg']) ?> <span>(<?= (int) $product['rating_count'] ?>)</span>
        </div>
        <div class="bh-card__footer">
            <span class="bh-price"><?= e(format_vnd((int) $product['price'])) ?></span>
            <?php if ($inStock) : ?>
                <div class="bh-card__actions">
                    <button type="button" class="bh-btn bh-btn--primary bh-btn--sm bh-card__add" data-add-to-cart="<?= (int) $product['id'] ?>" aria-label="Thêm vào giỏ">
                        <i class="bi bi-cart-plus"></i><span class="bh-card__add-label">Thêm vào giỏ</span>
                    </button>
                    <div class="bh-qty bh-qty--sm">
                        <button type="button" data-step="-1" aria-label="Giảm">−</button>
                        <input type="number" value="1" min="1" max="<?= (int) $product['stock_quantity'] ?>" aria-label="Số lượng" data-card-qty>
                        <button type="button" data-step="1" aria-label="Tăng">+</button>
                    </div>
                </div>
            <?php else : ?>
                <span class="badge text-bg-secondary align-self-start">Hết hàng</span>
            <?php endif; ?>
        </div>
    </div>
</article>