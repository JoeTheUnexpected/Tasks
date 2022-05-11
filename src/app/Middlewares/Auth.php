<?php


namespace App\Middlewares;


use App\Core\App;
use App\Core\Exception;
use App\Core\Middleware;

class Auth extends Middleware
{
    public function execute()
    {
        if (is_null(App::getInstance()->user)) {
            throw new Exception('Вы не авторизованы', 403);
        }
    }
}