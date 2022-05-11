<?php

use App\Controllers\AuthController;
use App\Controllers\SiteController;
use App\Controllers\TaskController;
use App\Core\App;

error_reporting(E_ERROR | E_PARSE);
require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = App::getInstance();

$app->router->get('/', [SiteController::class, 'index']);
$app->router->get('/test', fn() => $app->view->renderView('test'))->middleware('auth');

$app->router->get('/tasks', [TaskController::class, 'index']);
$app->router->get('/tasks/create', [TaskController::class, 'create'])->middleware('auth');
$app->router->post('/tasks/create', [TaskController::class, 'store'])->middleware('auth');
$app->router->get('/tasks/{id}', [TaskController::class, 'show']);
$app->router->get('/tasks/{id}/edit', [TaskController::class, 'edit'])->middleware('admin');
$app->router->patch('/tasks/{id}', [TaskController::class, 'update'])->middleware('admin');
$app->router->delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('admin');

$app->router->get('/register', [AuthController::class, 'register'])->middleware('guest');
$app->router->post('/register', [AuthController::class, 'store']);
$app->router->post('/login', [AuthController::class, 'login'])->middleware('guest');
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->run();
