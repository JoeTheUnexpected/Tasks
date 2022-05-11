<label for="<?= $id ?>" class="form-label"><?= $label ?></label>
<textarea class="form-control<?= $error ? ' is-invalid' : '' ?>" id="<?= $id ?>" name="<?= $name ?>"><?= $value ?></textarea>
<?php if ($error): ?>
<div id="<?= $id . 'Feedback' ?>" class="invalid-feedback">
    <?= $error ?>
</div>
<?php endif;
