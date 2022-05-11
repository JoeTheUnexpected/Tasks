<?php

use App\Core\App;
use App\Core\Component;

$this->title = 'Редактирование задачи'; ?>

<h1><?= $this->title ?></h1>

<?php
Component::include('messages/success');
Component::include('messages/errors');
?>

<h2>Имя пользователя</h2>
<p><?= $task->user_name ?></p>

<h2>Email</h2>
<p><?= $task->user_email ?></p>

<form action="<?= "/tasks/{$task->id}" ?>" method="post">
    <input type="hidden" name="_method" value="patch">

    <?php
    Component::include('inputs/textarea', [
        'id' => 'text',
        'label' => $task->labels()['text'],
        'name' => 'text',
        'value' => App::getInstance()->session->old('text', $task->text),
        'error' => App::getInstance()->session->getFirstError('text')
    ]);

    Component::include('inputs/checkbox', [
        'id' => 'completed',
        'label' => $task->labels()['completed'],
        'name' => 'completed',
        'value' => App::getInstance()->session->old('completed', $task->completed),
    ]);
    ?>

    <button type="submit" class="btn btn-primary mt-3">Редактировать</button>
</form>

<form action="<?= "/tasks/{$task->id}" ?>" method="post">
    <input type="hidden" name="_method" value="delete">

    <button type="submit" class="btn btn-danger mt-3">Удалить</button>
</form>
