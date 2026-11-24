<?php
/** @var \Throwable|null $exception */
$rawHome = (string) \App\Core\Auth::homeUrl();
$homeUrl = preg_match('#^https?://#i', $rawHome) ? $rawHome : url(ltrim($rawHome, '/'));
$showDebug = !empty($exception) && in_array(strtolower((string) config_value('APP_DEBUG', 'false')), ['1', 'true', 'yes', 'on'], true);
?>
<section class="container bh-error">
    <div class="bh-error__code">419</div>
    <h1 class="h3">Phiên làm việc đã hết hạn</h1>
    <p class="text-muted">Vui lòng tải lại trang và thử lại.</p>
    <?php if ($showDebug) : ?>
        <pre class="text-start small bg-white border rounded p-3"><?= e($exception->getMessage() . ' @ ' . $exception->getFile() . ':' . $exception->getLine()) ?></pre>
    <?php endif; ?>
    <a class="btn btn-lg" style="background:var(--bh-color-caramel);color:#fff" href="<?= e($homeUrl) ?>"><?= \App\Core\Auth::isAdmin() ? 'Về trang quản trị' : 'Về trang chủ' ?></a>
</section>