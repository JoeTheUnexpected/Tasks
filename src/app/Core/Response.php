<?php


namespace App\Core;


class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $path): void
    {
        header("Location: $path");
        die;
    }

    public function back(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}