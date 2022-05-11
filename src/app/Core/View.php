<?php


namespace App\Core;


class View
{
    public string $title = '';
    public string $layout = 'main';

    public function renderView(string $view, array $params = []): void
    {
        $content = $this->getContent($view, $params);
        $layout = $this->getLayout();

        echo str_replace('{{content}}', $content, $layout);
    }

    private function getLayout(): string
    {
        ob_start();
        include_once App::$ROOT_PATH . "/Views/layouts/{$this->layout}.php";
        return ob_get_clean();
    }

    private function getContent(string $view, array $params): string
    {
        extract($params);

        ob_start();
        include_once App::$ROOT_PATH . "/Views/$view.php";
        return ob_get_clean();
    }
}