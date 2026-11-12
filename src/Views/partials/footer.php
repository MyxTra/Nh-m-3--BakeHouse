<footer class="bh-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <a class="bh-logo d-inline-block mb-3" href="<?= e(url('/')) ?>">Bake<span class="bh-logo__accent">House</span></a>
                <p>Nguyên vật liệu và dụng cụ làm bánh chất lượng, giao nhanh tận nơi cho người yêu bếp bánh.</p>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Khám phá</h5>
                <a href="<?= e(url('/products')) ?>">Sản phẩm</a><a href="<?= e(url('/about')) ?>">Giới thiệu</a><a href="<?= e(url('/gallery')) ?>">Thư viện</a><a href="<?= e(url('/blog')) ?>">Blog</a>
            </div>
            <div class="col-6 col-lg-3">
                <h5>Hỗ trợ</h5>
                <a href="<?= e(url('/faq')) ?>">Câu hỏi thường gặp</a><a href="<?= e(url('/privacy')) ?>">Chính sách bảo mật</a><a href="<?= e(url('/terms')) ?>">Điều khoản</a><a href="<?= e(url('/contact')) ?>">Liên hệ</a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <h5>Liên hệ</h5>
                <div><i class="bi bi-geo-alt me-2"></i>12 Nguyễn Huệ, Quận 1, TP.HCM</div>
                <div><i class="bi bi-telephone me-2"></i>0900 000 001</div>
                <div><i class="bi bi-envelope me-2"></i>hello@bakehouse.vn</div>
            </div>
        </div>
        <div class="bh-footer__bottom">&copy; <?= date('Y') ?> BakeHouse. <?= e(lang('app.tagline')) ?></div>
    </div>
</footer>