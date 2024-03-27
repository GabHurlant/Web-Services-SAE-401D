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

function verifyApiKey($key)
{
    $expected_key = "e8f1997c763";
    return $key === $expected_key;
}

switch ($method) {
    case 'GET':
        if (!empty($_GET['action'])) {
            switch ($_GET['action']) {
                case 'products':
                    $products = $entityManager->getRepository(Products::class)->findAll();
                    echo json_encode($products);
                    break;
                case 'productById':
                    $productId = $_GET['id'];
                    $product = $entityManager->getRepository(Products::class)->find($productId);
                    echo json_encode($product);
                    break;
                case 'productByCategory':
                    $categoryName = $_GET['category'];
                    $category = $entityManager->getRepository(Categories::class)->findOneBy(['category_name' => $categoryName]);
                    $products = $category ? $category->getProducts()->toArray() : [];
                    echo json_encode($products);
                    break;
                case 'productByBrand':
                    $brandName = $_GET['brand'];
                    $brand = $entityManager->getRepository(Brands::class)->findOneBy(['brand_name' => $brandName]);
                    $products = $brand ? $brand->getProducts()->toArray() : [];
                    echo json_encode($products);
                    break;
                case 'brands':
                    $brands = $entityManager->getRepository(Brands::class)->findAll();
                    echo json_encode($brands);
                    break;
                case 'stores':
                    $stores = $entityManager->getRepository(Stores::class)->findAll();
                    echo json_encode($stores);
                    break;
                case 'categories':
                    $categories = $entityManager->getRepository(Categories::class)->findAll();
                    echo json_encode($categories);
                    break;
                default:
                    $response = array("status" => 0, "message" => "Nothing to show");
                    echo json_encode($response);
                    break;
            }
        }

    case 'POST':
        // Handle POST request
        if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
            $response = array("status" => 0, "message" => "API Key is invalid");
            echo json_encode($response);
            exit(); // Arrête l'exécution du script si la clé API n'est pas valide
        };
        if (!empty($_POST['action'])) {
            switch ($_POST['action']) {
                case 'addBrand':
                    $brandName = $_POST['name'];
                    $brand = new Brands();
                    $brand->setBrandName($brandName);
                    $entityManager->persist($brand);
                    $entityManager->flush();
                    echo json_encode($brand);
                    break;

                case 'addCategory':
                    $categoryName = $_POST['name'];
                    $category = new Categories();
                    $category->setCategoryName($categoryName);
                    $entityManager->persist($category);
                    $entityManager->flush();
                    echo json_encode($category);
                    break;

                case 'addProduct':
                    $productName = $_POST['name'];
                    $brandId = $_POST['brand'];
                    $categoryId = $_POST['category'];
                    $price = $_POST['price'];
                    $year = $_POST['year'];
                    $brand = $entityManager->getRepository(Brands::class)->find($brandId);
                    $category = $entityManager->getRepository(Categories::class)->find($categoryId);
                    $product = new Products();
                    $product->setProductName($productName);
                    $product->setBrand($brand);
                    $product->setCategory($category);
                    $product->setListPrice($price);
                    $product->setModelYear($year);
                    $entityManager->persist($product);
                    $entityManager->flush();
                    echo json_encode($product);
                    break;

                case 'addStock':
                    $storeId = $_POST['store'];
                    $productId = $_POST['product'];
                    $quantity = $_POST['quantity'];
                    $store = $entityManager->getRepository(Stores::class)->find($storeId);
                    $product = $entityManager->getRepository(Products::class)->find($productId);
                    $stock = new Stocks();
                    $stock->setStore($store);
                    $stock->setProduct($product);
                    $stock->setQuantity($quantity);
                    $entityManager->persist($stock);
                    $entityManager->flush();
                    echo json_encode($stock);
                    break;
            }
        }
        break;
}
