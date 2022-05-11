<?php


namespace App\Core;


class Component
{
    public static function include(string $component, array $data = []): string
    {
        extract($data);

        include App::$ROOT_PATH . "/Components/$component.php";
        return ob_get_contents();
    }
}