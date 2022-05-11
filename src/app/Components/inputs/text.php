<label for="<?= $id ?>" class="form-label"><?= $label ?></label>
<input type="text" class="form-control<?= $error ? ' is-invalid' : '' ?>" id="<?= $id ?>" name="<?= $name ?>" value="<?= $value ?>">
<?php if ($error): ?>
<div id="<?= $id . 'Feedback' ?>" class="invalid-feedback">
    <?= $error ?>
</div>
<?php endif;