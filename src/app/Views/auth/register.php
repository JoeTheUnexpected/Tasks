<?php

use App\Core\App;
use App\Core\Component;
use App\Models\User;

$user = new User;

$this->title = 'Регистрация';
?>

<h1><?= $this->title ?></h1>

<?php
Component::include('messages/success');
Component::include('messages/errors');
?>

<form action="" method="post">
    <?php
    Component::include('inputs/text', [
        'id' => 'name',
        'label' => $user->labels()['name'],
        'name' => 'name',
        'value' => App::getInstance()->session->old('name', $user->name),
        'error' => App::getInstance()->session->getFirstError('name')
    ]);

    Component::include('inputs/email', [
        'id' => 'email',
        'label' => $user->labels()['email'],
        'name' => 'email',
        'value' => App::getInstance()->session->old('email', $user->email),
        'error' => App::getInstance()->session->getFirstError('email')
    ]);

    Component::include('inputs/password', [
        'id' => 'password',
        'label' => $user->labels()['password'],
        'name' => 'password',
        'value' => App::getInstance()->session->old('password', $user->password),
        'error' => App::getInstance()->session->getFirstError('password')
    ]); ?>

    <button type="submit" class="btn btn-primary mt-3">Зарегистрироваться</button>
</form>
