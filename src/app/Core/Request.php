<?php


namespace App\Core;


class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $pos = strpos($path, '?');

        if ($pos === false) {
            return $path;
        }

        return substr($path, 0, $pos);
    }

    public function getMethod(): string
    {
        if (isset($_POST['_method'])) {
            return strtolower($_POST['_method']);
        }

        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}