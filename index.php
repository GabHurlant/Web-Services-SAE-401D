<?php

/**
 * Allows cross-origin requests from all origins.
 */
header("Access-Control-Allow-Origin: *");

/**
 * Allows the following HTTP methods: GET, POST, PUT, DELETE.
 */
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

/**
 * Allows the Content-Type header in HTTP requests.
 */
header("Access-Control-Allow-Headers: Content-Type");

/**
 * Includes the bootstrap file from the app directory.
 */
require_once __DIR__ . "/app/bootstrap.php";

/**
 * Includes the Router file from the app directory.
 */
require __DIR__ . "/app/Router.php";

/**
 * Includes the routes configuration file from the app directory.
 */
$routes = require __DIR__ . "/app/config/routes.php";

/**
 * Creates a new instance of the Router class.
 */
$router = new Router();

/**
 * Gets the HTTP method of the current request.
 */
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Gets the URI of the current request.
 */
$url = $_SERVER['REQUEST_URI'];

/**
 * Adds each route from the routes configuration to the router.
 */
foreach ($routes as $route) {
    $router->addRoute($route['method'], $route['path'], [$route['controller'], $route['action']]);
}

/**
 * Dispatches the router to handle the current request.
 */
$router->dispatch($method, $url);
