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
        } elseif (isset($_GET['categories'])) {
            $categories = $entityManager->getRepository(Categories::class)->findAll();
            echo json_encode($categories);
        } elseif (isset($_GET['brands'])) {
            $brands = $entityManager->getRepository(Brands::class)->findAll();
            echo json_encode($brands);
        } elseif (isset($_GET['employees'])) {
            $employees = $entityManager->getRepository(Employees::class)->findAll();
            echo json_encode($employees);
        } elseif (isset($_GET['stocks'])) {
            $stocks = $entityManager->getRepository(Stocks::class)->findAll();
            echo json_encode($stocks);
        } elseif (isset($_GET['stores'])) {
            $stores = $entityManager->getRepository(Stores::class)->findAll();
            echo json_encode($stores);
        } else {
            $response = array("status" => 0, "message" => "Nothing to show");
            echo json_encode($response);
        }
        break;
}
