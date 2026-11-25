<?php /** @var array $contact */
$labels = ['new' => 'Chưa phản hồi', 'replied' => 'Đã phản hồi'];
$pills = ['new' => 'adm-pill--warn', 'replied' => 'adm-pill--ok'];
$statusKey = ($contact['status'] === 'replied') ? 'replied' : 'new';
$id = (int) $contact['id'];
$hasReply = !empty($contact['admin_reply']);

$backStatus = isset($_GET['status']) && in_array($_GET['status'], ['new', 'replied'], true) ? (string) $_GET['status'] : '';
$backQ = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$backFromDate = isset($_GET['from_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $_GET['from_date'])
    ? (string) $_GET['from_date']
    : '';
$backToDate = isset($_GET['to_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $_GET['to_date'])
    ? (string) $_GET['to_date']
    : '';
$backPage = isset($_GET['page']) && ctype_digit((string) $_GET['page']) && (int) $_GET['page'] > 1
    ? (int) $_GET['page']
    : 1;
$backParams = array_filter([
    'status' => $backStatus !== '' ? $backStatus : null,
    'q' => $backQ !== '' ? $backQ : null,
    'from_date' => $backFromDate !== '' ? $backFromDate : null,
    'to_date' => $backToDate !== '' ? $backToDate : null,
    'page' => $backPage > 1 ? $backPage : null,
], static fn (mixed $val): bool => $val !== null);
$backUrl = url('admin/contacts') . ($backParams !== [] ? '?' . http_build_query($backParams) : ''); ?>
<div class="mb-3"><a class="adm-back" href="<?= e($backUrl) ?>"><i class="bi bi-arrow-left"></i> Danh sách liên hệ</a></div>
<div class="adm-contact-detail">
    <section class="adm-card">
        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-3">
            <h2 class="adm-contact-detail__title">Nội dung liên hệ</h2>
            <span class="adm-pill <?= e($pills[$statusKey]) ?>"><?= e($labels[$statusKey]) ?></span>
        </div>
        <p class="adm-contact-detail__msg"><?= nl2br(e($contact['message'])) ?></p>

        <?php if ($hasReply) : ?>
            <div class="adm-review__reply mt-3"><strong>Đã phản hồi</strong>
                <?php if (!empty($contact['admin_replied_at'])) : ?><span class="adm-review__meta">· <?= e(date('d/m/Y H:i', strtotime($contact['admin_replied_at']))) ?></span><?php endif; ?>
                <br><?= nl2br(e($contact['admin_reply'])) ?></div>
        <?php endif; ?>

        <?php $replyError = form_error('admin_reply'); ?>
        <?php if ($hasReply) : ?><details class="adm-reply mt-3"<?= $replyError !== '' ? ' open' : '' ?>>
            <summary class="bh-btn bh-btn--outline bh-btn--sm"><i class="bi bi-pencil"></i> Sửa phản hồi</summary><?php endif; ?>
        <form class="mt-3" method="post" action="<?= e(url('admin/contacts/' . $id . '/reply')) ?>" novalidate><?= csrf_field() ?>
            <?php if ($backStatus !== '') : ?><input type="hidden" name="status" value="<?= e($backStatus) ?>"><?php endif; ?>
            <?php if ($backQ !== '') : ?><input type="hidden" name="q" value="<?= e($backQ) ?>"><?php endif; ?>
            <?php if ($backFromDate !== '') : ?><input type="hidden" name="from_date" value="<?= e($backFromDate) ?>"><?php endif; ?>
            <?php if ($backToDate !== '') : ?><input type="hidden" name="to_date" value="<?= e($backToDate) ?>"><?php endif; ?>
            <?php if ($backPage > 1) : ?><input type="hidden" name="page" value="<?= (int) $backPage ?>"><?php endif; ?>
            <label class="form-label" for="admin_reply"><?= $hasReply ? 'Sửa phản hồi' : 'Phản hồi khách hàng' ?></label>
            <textarea class="form-control" id="admin_reply" name="admin_reply" rows="5" maxlength="2000" placeholder="Viết phản hồi…" required><?= old('admin_reply', (string) ($contact['admin_reply'] ?? '')) ?></textarea><?= $replyError ?>
            <?php if ($contact['user_id'] === null) : ?>
                <div class="form-text">Khách gửi khi chưa đăng nhập nên không xem được phản hồi trên web. Hãy liên hệ lại qua email hoặc số điện thoại bên cạnh.</div>
            <?php endif; ?>
            <div class="d-flex gap-2 mt-3">
                <?php if ($hasReply) : ?><button class="bh-btn bh-btn--outline" type="button" onclick="this.closest('details').open = false">Hủy</button><?php endif; ?>
                <button class="bh-btn bh-btn--primary" type="submit"><i class="bi bi-send"></i> <?= $hasReply ? 'Cập nhật phản hồi' : 'Gửi phản hồi' ?></button>
            </div>
        </form>
        <?php if ($hasReply) : ?></details><?php endif; ?>
    </section>

    <aside class="adm-card">
        <h2 class="adm-contact-detail__title mb-3">Người gửi</h2>
        <dl class="adm-contact-detail__info">
            <dt>Họ tên</dt><dd><?= e($contact['full_name']) ?></dd>
            <dt>Email</dt><dd><?= !empty($contact['email']) ? '<a href="mailto:' . e($contact['email']) . '">' . e($contact['email']) . '</a>' : '<span class="bh-muted">—</span>' ?></dd>
            <dt>Số điện thoại</dt><dd><?= !empty($contact['phone']) ? '<a href="tel:' . e($contact['phone']) . '">' . e($contact['phone']) . '</a>' : '<span class="bh-muted">—</span>' ?></dd>
            <dt>Tài khoản</dt><dd><?= $contact['user_id'] !== null ? e((string) ($contact['account_email'] ?: $contact['account_phone'] ?: ('#' . $contact['user_id']))) : '<span class="bh-muted">Khách vãng lai</span>' ?></dd>
            <dt>Gửi lúc</dt><dd><?= e(date('d/m/Y H:i', strtotime($contact['created_at']))) ?></dd>
        </dl>
    </aside>
</div>