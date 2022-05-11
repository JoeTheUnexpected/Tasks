<?php if (\App\Core\App::getInstance()->session->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
    <?php foreach (\App\Core\App::getInstance()->session->getAllErrors() as $key => $errors):
        foreach ($errors['values'] as $error):?>
            <p><?= $error ?></p>
        <?php endforeach;
        endforeach; ?>
    </div>
<?php endif;
