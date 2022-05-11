<?php

use App\Core\App;
use App\Core\Component;
use App\Models\Task;

$task = new Task;

$this->title = 'Создание задачи'; ?>

<h1><?= $this->title ?></h1>

<?php
Component::include('messages/success');
Component::include('messages/errors');
?>

<form action="" method="post">
    <?php
    Component::include('inputs/text', [
        'id' => 'user_name',
        'label' => $task->labels()['user_name'],
        'name' => 'user_name',
        'value' => App::getInstance()->session->old('user_name', $task->user_name),
        'error' => App::getInstance()->session->getFirstError('user_name')
    ]);

    Component::include('inputs/email', [
        'id' => 'user_email',
        'label' => $task->labels()['user_email'],
        'name' => 'user_email',
        'value' => App::getInstance()->session->old('user_email', $task->user_email),
        'error' => App::getInstance()->session->getFirstError('user_email')
    ]);

    Component::include('inputs/textarea', [
        'id' => 'text',
        'label' => $task->labels()['text'],
        'name' => 'text',
        'value' => App::getInstance()->session->old('text', $task->text),
        'error' => App::getInstance()->session->getFirstError('text')
    ]); ?>

    <button type="submit" class="btn btn-primary mt-3">Создать</button>
</form>
