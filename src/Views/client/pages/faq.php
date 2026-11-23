<?php
partial('hero', ['heroTitle' => 'Câu hỏi thường gặp', 'crumbs' => [['Trang chủ', url('')], ['FAQ', null]]]);
$faqs = [
    ['BakeHouse giao hàng trong bao lâu?', 'Đơn nội thành thường được xử lý và giao trong ngày tùy khu vực và thời điểm đặt đơn.'],
    ['Thanh toán VietQR xác nhận thế nào?', 'Sau khi đặt đơn VietQR, bạn quét mã đúng số tiền và nội dung mã đơn; cửa hàng sẽ xác nhận sau khi nhận được tiền.'],
    ['Ai có thể đánh giá sản phẩm?', 'Chỉ khách hàng đã có đơn hàng giao thành công mới có thể đánh giá sản phẩm.'],
];
?>
<div class="container py-5" style="max-width:820px">
    <div class="accordion" id="bhFaq">
        <?php foreach ($faqs as $i => [$q, $a]) : ?>
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button<?= $i ? ' collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>"><?= e($q) ?></button></h2>
                <div id="faq<?= $i ?>" class="accordion-collapse collapse<?= $i ? '' : ' show' ?>" data-bs-parent="#bhFaq"><div class="accordion-body"><?= e($a) ?></div></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>