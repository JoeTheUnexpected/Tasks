<?php $this->title = "Задача #{$task->id}" ?>

<h1 class="d-inline-block"><?= $this->title ?></h1>

<?php if (\App\Core\App::getInstance()->user && \App\Core\App::getInstance()->user->isAdmin()): ?>
    <span class="h3"> / </span><a href="<?= "/tasks/{$task->id}/edit" ?>" class="h3">Редактировать</a>
<?php endif; ?>

<h2>Имя пользователя</h2>
<p><?= $task->user_name ?></p>

<h2>Email</h2>
<p><?= $task->user_email ?></p>

<h2>Текст</h2>
<p><?= $task->text ?></p>

<?php if ($task->completed): ?>
<h5 class="text-success">Завершена</h5>
<?php endif;
