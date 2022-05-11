<?php


namespace App\Core;


class Router
{
    private array $routes = [];
    private array $routeParams = [];

    private string $method;
    private string $url;
    private array $middlewares = [];

    public function get(string $url, array|callable $callback)
    {
        $this->routes['get'][$url] = $callback;
        $this->method = 'get';
        $this->url = $url;
        return $this;
    }

    public function post(string $url, array|callable $callback)
    {
        $this->routes['post'][$url] = $callback;
        $this->method = 'post';
        $this->url = $url;
        return $this;
    }

    public function patch(string $url, array|callable $callback)
    {
        $this->routes['patch'][$url] = $callback;
        $this->method = 'patch';
        $this->url = $url;
        return $this;
    }

    public function delete(string $url, array|callable $callback)
    {
        $this->routes['delete'][$url] = $callback;
        $this->method = 'delete';
        $this->url = $url;
        return $this;
    }

    public function resolve(App $app)
    {
        $method = $app->request->getMethod();
        $path = $app->request->getPath();

        $callback = $this->routes[$method][$path] ?? $this->getCallbackWithParams($method, $path) ?? null;

        if (is_null($callback)) {
            throw new Exception('Страница не найдена', 404);
        }

        foreach ($this->middlewares[$method][$path] as $middleware) {
            $middleware->execute();
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0];
        }

        call_user_func_array($callback, $this->routeParams);
    }

    private function getCallbackWithParams(string $method, string $path)
    {
        foreach ($this->routes[$method] as $route => $callback) {
            $routeRegex = '@^' . preg_replace('/\{\w+}/', '(\w+)', $route) . '$@';

            if (preg_match_all($routeRegex, $path, $valueMatches)) {
                foreach ($valueMatches as $key => $value) {
                    if ($key === 0) {
                        continue;
                    }

                    $this->routeParams[] = $value[0];
                }

                $this->middlewares[$method][$path] = $this->middlewares[$method][$route];

                return $this->routes[$method][$route];
            }
        }

        return null;
    }

    public function middleware(string $middlewareName)
    {
        $middleware = '\\App\\Middlewares\\' . ucfirst(strtolower($middlewareName));
        if (class_exists($middleware)) {
            $this->middlewares[$this->method][$this->url][] = new $middleware;
        }

        return $this;
    }
}