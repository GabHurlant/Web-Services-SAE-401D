<?php

/**
 * Class Router
 *
 * A simple router class for handling HTTP requests.
 */
class Router
{
    /**
     * @var array $routes The array to store routes.
     */
    private $routes = [];

    /**
     * Adds a route to the routes array.
     *
     * @param string $method The HTTP method for the route.
     * @param string $url The URL pattern for the route.
     * @param callable $handler The handler to be called when the route is matched.
     */
    public function addRoute($method, $url, $handler)
    {
        $this->routes[] = [$method, $url, $handler];
    }

    /**
     * Dispatches the router to handle the current request.
     *
     * @param string $method The HTTP method of the current request.
     * @param string $url The URL of the current request.
     */
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
        // If no route is found, return a 404 response.
        header('HTTP/1.0 404 Not Found');
        echo '404 - Not Found';
    }
}
