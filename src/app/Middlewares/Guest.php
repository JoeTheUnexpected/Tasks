<?php


namespace App\Middlewares;


use App\Core\App;
use App\Core\Middleware;

class Guest extends Middleware
{

    public function execute()
    {
        if (!is_null(App::getInstance()->user)) {
            App::getInstance()->response->redirect('/');
        }
    }
}