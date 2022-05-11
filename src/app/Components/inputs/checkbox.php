<div class="mb-3 form-check">
    <label for="<?= $id ?>" class="form-check-label"><?= $label ?></label>
    <input type="checkbox" class="form-check-input" id="<?= $id ?>" name="<?= $name ?>" <?= \App\Core\App::getInstance()->session->old('completed', $value ? 'checked' : '') ?>>
</div>
