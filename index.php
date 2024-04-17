<?php

/**
 * Set CORS headers for allowing cross-origin requests.
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Include the bootstrap file.
require_once __DIR__ . "/app/bootstrap.php";

// Include the Router class.
require __DIR__ . "/app/Router.php";

// Load routes configuration.
$routes = require __DIR__ . "/app/config/routes.php";

// Create a new Router instance.
$router = new Router();

// Get the request method and URL.
$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

//
