<?php

use App\Core\App;
use App\Core\Component;

Component::include('inputs/text', [
    'id' => 'user_name',
    'label' => $model->labels()['user_name'],
    'name' => 'user_name',
    'value' => App::getInstance()->session->old('user_name', $model->user_name),
    'error' => App::getInstance()->session->getFirstError('user_name')
]);

Component::include('inputs/email', [
    'id' => 'user_email',
    'label' => $model->labels()['user_email'],
    'name' => 'user_email',
    'value' => App::getInstance()->session->old('user_email', $model->user_email),
    'error' => App::getInstance()->session->getFirstError('user_email')
]);

Component::include('inputs/textarea', [
    'id' => 'text',
    'label' => $model->labels()['text'],
    'name' => 'text',
    'value' => App::getInstance()->session->old('text', $model->text),
    'error' => App::getInstance()->session->getFirstError('text')
]);