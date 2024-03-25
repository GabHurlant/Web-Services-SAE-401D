<?php

//API pour les magasins

//Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");
header("Access-Control-Allow-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Inclusion des fichiers de configuration et d'accès aux données
require_once 'bootstrap.php';

use Entity\Stores;
use Entity\Products;
use Entity\Categories;
use Entity\Brands;
use Entity\Employees;
use Entity\Stocks;

//Récupération de la méthode HTTP

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        //Code pour la méthode GET
        if (isset($_GET['products'])) {
            $products = $entityManager->getRepository(Products::class)->findAll();
            echo json_encode($products);
        } else {
            $response = array("status" => 0, "message" => "Nothing to show");
            echo json_encode($response);
        }
        break;
}
