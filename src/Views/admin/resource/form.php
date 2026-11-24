<?php /** @var array $config */ /** @var ?array $row */ /** @var string $action */
$base = url('admin/' . ltrim((string) $config['route'], '/'));
$formAction = str_starts_with($action, 'http') ? $action : url(ltrim($action, '/')); ?>
<form class="adm-card adm-form" style="max-width:520px;display:block" method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <?php foreach ($config['fields'] as $field) :
        $name = (string) $field['name'];
        $value = $row[$name] ?? ''; ?>
        <?php if ($field['type'] === 'image') : ?>
            <div class="mb-3"><label class="form-label" for="f_<?= e($name) ?>"><?= e($field['label']) ?></label>
                <input class="form-control" type="file" id="f_<?= e($name) ?>" name="<?= e($name) ?>" accept="image/jpeg,image/png,image/webp" data-preview="#preview_<?= e($name) ?>"><?= form_error($name) ?>
                <div id="preview_<?= e($name) ?>" class="mt-2"><?php if ($value !== '') : ?><img class="bh-thumb" style="width:96px;height:96px" src="<?= e(img_url((string) $value)) ?>" alt=""><?php endif; ?></div>
                <?php if ($value !== '' && empty($field['required_on_create'])) : ?>
                    <div class="form-check mt-1"><input class="form-check-input" type="checkbox" id="f_<?= e($name) ?>_remove" name="<?= e($name) ?>_remove" value="1"><label class="form-check-label" for="f_<?= e($name) ?>_remove">Xóa ảnh hiện tại</label></div>
                <?php endif; ?>
            </div>
        <?php elseif ($field['type'] === 'checkbox') :
            $checked = old($name) !== '' ? old($name) === '1' : (!$row || !empty($value)); ?>
            <div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" id="f_<?= e($name) ?>" name="<?= e($name) ?>" value="1"<?= $checked ? ' checked' : '' ?>><label class="form-check-label" for="f_<?= e($name) ?>"><?= e($field['label']) ?></label></div>
        <?php else : ?>
            <div class="mb-3"><label class="form-label" for="f_<?= e($name) ?>"><?= e($field['label']) ?></label>
                <input class="form-control" id="f_<?= e($name) ?>" name="<?= e($name) ?>" value="<?= old($name, (string) $value) ?>"<?= isset($field['placeholder']) ? ' placeholder="' . e($field['placeholder']) . '"' : '' ?><?= str_contains((string) ($field['rules'] ?? ''), 'required') ? ' required' : '' ?><?= $name === $config['fields'][0]['name'] ? ' autofocus' : '' ?>><?= form_error($name) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="d-flex gap-2"><button class="bh-btn bh-btn--primary" type="submit">Lưu</button><a class="bh-btn bh-btn--outline" href="<?= e($base) ?>">Hủy</a></div>
</form>