<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/app/bootstrap.php";
require __DIR__ . "/app/Router.php";
$routes = require __DIR__ . "/app/config/routes.php";

$router = new Router();

$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

foreach ($routes as $route) {
    $router->addRoute($route['method'], $route['path'], [$route['controller'], $route['action']]);
}

$router->dispatch($method, $url);
