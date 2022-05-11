<?php


namespace App\Middlewares;


use App\Core\App;
use App\Core\Exception;
use App\Core\Middleware;

class Admin extends Middleware
{
    public function execute()
    {
        if (is_null(App::getInstance()->user) || !App::getInstance()->user->isAdmin()) {
            throw new Exception('Нет прав', 403);
        }
    }
}