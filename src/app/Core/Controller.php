<?php


namespace App\Core;


abstract class Controller
{
    protected function render(string $view, array $params = [])
    {
        App::getInstance()->view->renderView($view, $params);
    }

    protected function setLayout(string $layout)
    {
        App::getInstance()->view->layout = $layout;
    }
}