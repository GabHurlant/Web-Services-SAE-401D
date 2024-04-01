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

                case 'employees':
                    if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
                        $response = array("status" => 0, "message" => "API Key is invalid");
                        echo json_encode($response);
                        exit(); // Arrête l'exécution du script si la clé API n'est pas valide
                    };
                    $employees = $entityManager->getRepository(Employees::class)->findAll();
                    echo json_encode($employees);
                    break;

                case 'employeeByStoreName':
                    if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
                        $response = array("status" => 0, "message" => "API Key is invalid");
                        echo json_encode($response);
                        exit(); // Arrête l'exécution du script si la clé API n'est pas valide
                    };
                    $storeName = $_GET['store'];
                    $store = $entityManager->getRepository(Stores::class)->find($storeName);
                    $employees = $store ? $store->getEmployees()->toArray() : [];
                    echo json_encode($employees);
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
                    // if (!isset($_POST['name']) || empty($_POST['name'])) {
                    //     $response = array("status" => 0, "message" => "Brand's name required");
                    //     echo json_encode($response);
                    //     exit();
                    // }
                    $brandName = $_POST['name'];
                    // $existingBrand = $entityManager->getRepository(Brands::class)->findOneBy(['brandName' => $brandName]);
                    // if ($existingBrand) {
                    //     $response = array("status" => 0, "message" => "already in the database");
                    //     echo json_encode($response);
                    //     exit();
                    // }
                    $brand = new Brands();
                    $brand->setBrandName($brandName);
                    $entityManager->persist($brand);
                    $entityManager->flush();
                    $response = array("status" => 1, "message" => "Brand added successfully", "data" => $brand);
                    echo json_encode($brand);
                    break;

                case 'addCategory':
                    if (!isset($_POST['name']) || empty($_POST['name'])) {
                        $response = array("status" => 0, "message" => "Category name required");
                        echo json_encode($response);
                        exit();
                    }
                    $categoryName = $_POST['name'];
                    $existingCategory = $entityManager->getRepository(Categories::class)->findOneBy(['categoryName' => $categoryName]);
                    if ($existingCategory) {
                        $response = array("status" => 0, "message" => "already in the database");
                        echo json_encode($response);
                        exit();
                    }
                    $category = new Categories();
                    $category->setCategoryName($categoryName);
                    $entityManager->persist($category);
                    $entityManager->flush();
                    $response = array("status" => 1, "message" => "Category added successfully", "data" => $category);
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

                case 'addEmployee':
                    $storeId = $_POST['store'];
                    $employeeName = $_POST['name'];
                    $employeeEmail = $_POST['email'];
                    $employeePassword = $_POST['password'];
                    $employeeRole = $_POST['role'];

                    $store = $entityManager->getRepository(Stores::class)->find($storeId);

                    $employee = new Employees();
                    $employee->setStore($store);
                    $employee->setEmployeeName($employeeName);
                    $employee->setEmployeeEmail($employeeEmail);
                    $employee->setEmployeePassword($employeePassword);
                    $employee->setEmployeeRole($employeeRole);

                    $entityManager->persist($employee);
                    $entityManager->flush();

                    echo json_encode($employee);
                    break;

                default:
                    $response = array("status" => 0, "message" => "problem to add");
                    echo json_encode($response);
                    break;
            }
        }
    case 'PUT':
        // Handle POST request
        if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
            $response = array("status" => 0, "message" => "API Key is invalid");
            echo json_encode($response);
            exit(); // Arrête l'exécution du script si la clé API n'est pas valide
        };

        if (!empty($_PUT['action'])) {
            switch ($_PUT['action']) {
                case 'updateStock':
                    $stockId = $_PUT['id'];
                    $quantity = $_PUT['quantity'];
                    $stock = $entityManager->getRepository(Stocks::class)->find($stockId);
                    $stock->setQuantity($quantity);
                    $entityManager->flush();
                    echo json_encode($stock);
                    break;

                case 'updateEmployee':
                    $employeeName = $_PUT['name'];
                    $employeeEmail = $_PUT['email'];
                    $employeePassword = $_PUT['password'];
                    $employeeRole = $_PUT['role'];

                    $employee = $entityManager->getRepository(Employees::class)->find($employeeName);
                    $employee->setEmployeeName($employeeName);
                    $employee->setEmployeeEmail($employeeEmail);
                    $employee->setEmployeePassword($employeePassword);
                    $employee->setEmployeeRole($employeeRole);

                    $entityManager->flush();

                    echo json_encode($employee);
                    break;

                case 'updateProduct':
                    $productId = $_PUT['id'];
                    $productName = $_PUT['name'];
                    $brandId = $_PUT['brand'];
                    $categoryId = $_PUT['category'];
                    $price = $_PUT['price'];
                    $year = $_PUT['year'];
                    $brand = $entityManager->getRepository(Brands::class)->find($brandId);
                    $category = $entityManager->getRepository(Categories::class)->find($categoryId);
                    $product = $entityManager->getRepository(Products::class)->find($productId);
                    $product->setProductName($productName);
                    $product->setBrand($brand);
                    $product->setCategory($category);
                    $product->setListPrice($price);
                    $product->setModelYear($year);
                    $entityManager->flush();
                    echo json_encode($product);
                    break;

                case 'updateBrand':
                    $brandName = $_PUT['name'];
                    $brand = $entityManager->getRepository(Brands::class)->find($brandName);
                    $brand->setBrandName($brandName);
                    $entityManager->flush();
                    echo json_encode($brand);
                    break;

                case 'updateCategory':
                    $categoryName = $_PUT['name'];
                    $category = $entityManager->getRepository(Categories::class)->find($categoryName);
                    $category->setCategoryName($categoryName);
                    $entityManager->flush();
                    echo json_encode($category);
                    break;

                case 'updateStore':
                    $storeId = $_PUT['id'];
                    $storeName = $_PUT['name'];
                    $storeAddress = $_PUT['address'];
                    $store = $entityManager->getRepository(Stores::class)->find($storeId);
                    $store->setStoreName($storeName);
                    $store->setStoreAddress($storeAddress);
                    $entityManager->flush();
                    echo json_encode($store);
                    break;
            }
        }
    case 'DELETE':
        // Handle DELETE requests
        if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
            $response = array("status" => 0, "message" => "API Key is invalid");
            echo json_encode($response);
            exit(); // Arrête l'exécution du script si la clé API n'est pas valide
        };

        if (!empty($_DELETE['action'])) {
            switch ($_DELETE['action']) {
                case 'deleteStock':
                    $stockId = $_DELETE['id'];
                    $stock = $entityManager->getRepository(Stocks::class)->find($stockId);
                    $entityManager->remove($stock);
                    $entityManager->flush();
                    echo json_encode($stock);
                    break;

                case 'deleteEmployee':
                    $employeeName = $_DELETE['Name'];
                    $employee = $entityManager->getRepository(Employees::class)->find($employeeName);
                    $entityManager->remove($employee);
                    $entityManager->flush();
                    echo json_encode($employee);
                    break;

                case 'deleteProduct':
                    $productName = $_DELETE['name'];
                    $product = $entityManager->getRepository(Products::class)->find($productName);
                    $entityManager->remove($product);
                    $entityManager->flush();
                    echo json_encode($product);
                    break;

                case 'deleteBrand':
                    $brandName = $_DELETE['name'];
                    $brand = $entityManager->getRepository(Brands::class)->find($brandName);
                    $entityManager->remove($brand);
                    $entityManager->flush();
                    echo json_encode($brand);
                    break;

                case 'deleteCategory':
                    $categoryName = $_DELETE['name'];
                    $category = $entityManager->getRepository(Categories::class)->find($categoryName);
                    $entityManager->remove($category);
                    $entityManager->flush();
                    echo json_encode($category);
                    break;
            }
        }
        break;
}
