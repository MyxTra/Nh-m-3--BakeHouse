<?php /** @var string $heroTitle */ /** @var array<int, array{0: string, 1: ?string}> $crumbs */ ?>
<section class="bh-hero">
    <h1 class="bh-hero__title"><?= e($heroTitle) ?></h1>
    <nav class="bh-breadcrumb" aria-label="breadcrumb">
        <?php foreach ($crumbs ?? [] as $index => [$label, $href]) :
            $resolvedHref = null;
            if ($href !== null && $href !== '') {
                $resolvedHref = preg_match('#^https?://#i', $href) ? $href : url(ltrim($href, '/'));
            } ?>
            <?= $index > 0 ? ' / ' : '' ?><?= $resolvedHref !== null ? '<a href="' . e($resolvedHref) . '">' . e($label) . '</a>' : e($label) ?>
        <?php endforeach; ?>
    </nav>
</section>