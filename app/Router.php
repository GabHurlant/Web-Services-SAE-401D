<?php

//router

class Router
{

    private $routes = [];

    public function addRoute($method, $url, $handler)
    {
        $this->routes[] = [$method, $url, $handler];
    }

    public function dispatch($method, $url)
    {
        foreach ($this->routes as $route) {
            list($routeMethod, $routeUrl, $handler) = $route;
            if ($method === $routeMethod) {
                if (preg_match('#^' . $routeUrl . '$#', $url, $matches)) {
                    call_user_func($handler, $matches);
                    return;
                }
            }
        }
        //if no route
        http_response_code(404);
        echo '404 Not Found';
    }
}
