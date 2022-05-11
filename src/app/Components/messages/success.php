<?php if (\App\Core\App::getInstance()->session->hasFlash('success')): ?>
<div class="alert alert-success" role="alert">
  <?= \App\Core\App::getInstance()->session->getFlash('success') ?>
</div>
<?php endif;
