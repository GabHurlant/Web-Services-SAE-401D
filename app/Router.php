<?php

/**
 * Class Router
 * 
 * A simple router class for handling HTTP requests.
 */
class Router
{

    /** @var array Stores registered routes */
    private $routes = [];

    /**
     * Add a new route to the router.
     *
     * @param string $method The HTTP method (GET, POST, PUT, DELETE, etc.).
     * @param string $url The URL pattern for the route.
     * @param callable $handler The handler function to be called when the route matches.
     * @return void
     */
    public function addRoute($method, $url, $handler)
    {
        $this->routes[] = [$method, $url, $handler];
    }

    /**
     * Dispatches the request to the appropriate route handler.
     *
     * @param string $method The HTTP method of the request.
     * @param string $url The URL of the request.
     * @return void
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
        // If no route matches, return a 404 Not Found response.
        header('HTTP/1.0 404 Not Found');
        echo '404 - Not Found';
    }
}
