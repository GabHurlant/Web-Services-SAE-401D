<?php

//API pour les magasins

//Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
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

                    $products = $entityManager->getRepository(Products::class)->getAllProducts();
                    echo json_encode($products);
                    break;

                case 'productById':
                    if (!isset($_GET['id'])) {
                        echo json_encode(array("status" => 0, "message" => "Product id is required"));
                        exit();
                    }

                    $id = $_GET['id'];
                    $product = $entityManager->getRepository(Products::class)->getProductById($id);
                    echo json_encode($product);
                    break;

                case 'productByCategory':
                    $categoryName = $_GET['category'];
                    $products = $entityManager->getRepository(Categories::class)->findProductsByCategoryName($categoryName);
                    echo json_encode($products);
                    break;

                case 'productByBrand':
                    $brandName = $_GET['brand'];
                    $products = $entityManager->getRepository(Brands::class)->findProductsByBrandName($brandName);
                    echo json_encode($products);
                    break;

                case 'brands':
                    $brands = $entityManager->getRepository(Brands::class)->getAllBrands();
                    echo json_encode($brands);
                    break;

                case 'stores':
                    $stores = $entityManager->getRepository(Stores::class)->getStores();
                    echo json_encode($stores);
                    break;

                case 'categories':
                    $categories = $entityManager->getRepository(Categories::class)->getAllCategories();
                    echo json_encode($categories);
                    break;

                case 'employees':
                    if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
                        $response = array("status" => 0, "message" => "API Key is invalid");
                        echo json_encode($response);
                        exit(); // Arrête l'exécution du script si la clé API n'est pas valide
                    };
                    $employees = $entityManager->getRepository(Employees::class)->getAllEmployees();
                    echo json_encode($employees);
                    break;

                case 'employeeByStoreName':
                    if (!isset($_GET['api_key']) || !verifyApiKey($_GET['api_key'])) {
                        $response = array("status" => 0, "message" => "API Key is invalid");
                        echo json_encode($response);
                        exit(); // Arrête l'exécution du script si la clé API n'est pas valide
                    };
                    if (!isset($_GET['store'])) {
                        echo json_encode(array("status" => 0, "message" => "Store name is required"));
                        exit();
                    }

                    $storeName = $_GET['store'];
                    $employees = $entityManager->getRepository(Stores::class)->getEmployeesByStoreName($storeName);
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
                    $brandName = $_GET['name'];
                    $brandRepository = $entityManager->getRepository(Brands::class);
                    $newBrand = $brandRepository->insertNewBrand($brandName);
                    echo json_encode($newBrand);
                    break;

                case 'addCategory':
                    $categoryName = $_POST['name'];
                    $categoryRepository = $entityManager->getRepository(Categories::class);
                    $newCategory = $categoryRepository->insertNewCategory($categoryName);
                    echo json_encode($newCategory);
                    break;

                case 'addProduct':
                    if (!isset($_POST['name'], $_POST['year'], $_POST['price'])) {
                        echo json_encode(array("status" => 0, "message" => "All fields are required"));
                        exit();
                    }

                    $productName = $_POST['name'];
                    $modelYear = $_POST['year'];
                    $listPrice = $_POST['price'];
                    $productRepository = $entityManager->getRepository(Products::class);
                    $newProduct = $productRepository->insertNewProduct($productName, $modelYear, $listPrice);
                    echo json_encode($newProduct);
                    break;

                case 'addStock':
                    try {

                        if (
                            !isset($_POST['store']) || empty($_POST['store']) ||
                            !isset($_POST['product']) || empty($_POST['product']) ||
                            !isset($_POST['quantity']) || empty($_POST['quantity'])
                        ) {
                            $response = array("status" => 0, "message" => "All fields are required");
                            echo json_encode($response);
                            exit();
                        }
                        $storeId = $_POST['store'];
                        $productId = $_POST['product'];
                        $quantity = $_POST['quantity'];
                        $store = $entityManager->getRepository(Stores::class)->find($storeId);
                        $product = $entityManager->getRepository(Products::class)->find($productId);

                        // Vérifiez si le stock existe déjà
                        $existingStock = $entityManager->getRepository(Stocks::class)->findOneBy(['store' => $store, 'product' => $product]);
                        if ($existingStock) {
                            $response = array("status" => 0, "message" => "Stock already in the database");
                            echo json_encode($response);
                            exit();
                        }

                        $stock = new Stocks();
                        $stock->setStore($store);
                        $stock->setProduct($product);
                        $stock->setQuantity($quantity);
                        $entityManager->persist($stock);
                        $entityManager->flush();
                        echo json_encode($response);
                    } catch (Exception $e) {
                        $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
                        echo json_encode($response);
                    }
                    break;

                case 'addEmployee':
                    try {
                        if (
                            !isset($_POST['store']) || empty($_POST['store']) ||
                            !isset($_POST['name']) || empty($_POST['name']) ||
                            !isset($_POST['email']) || empty($_POST['email']) ||
                            !isset($_POST['password']) || empty($_POST['password']) ||
                            !isset($_POST['role']) || empty($_POST['role'])
                        ) {
                            $response = array("status" => 0, "message" => "All fields are required");
                            echo json_encode($response);
                            exit();
                        }
                        $storeId = $_POST['store'];
                        $employeeName = $_POST['name'];
                        $employeeEmail = $_POST['email'];
                        $employeePassword = $_POST['password'];
                        $employeeRole = $_POST['role'];

                        // Appel de la fonction addEmployee
                        $response = $entityManager->getRepository(Employees::class)->addEmployee($storeId, $employeeName, $employeeEmail, $employeePassword, $employeeRole);
                        echo json_encode($response);
                    } catch (Exception $e) {
                        $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
                        echo json_encode($response);
                    }
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
                    if (
                        !isset($_PUT['id']) || empty($_PUT['id']) ||
                        !isset($_PUT['quantity']) || empty($_PUT['quantity'])
                    ) {
                        $response = array("status" => 0, "message" => "Stock ID and quantity are required");
                        echo json_encode($response);
                        exit();
                    }

                    $stockId = $_PUT['id'];
                    $quantity = $_PUT['quantity'];
                    $stock = $entityManager->getRepository(Stocks::class)->find($stockId);
                    if (!$stock) {
                        $response = array("status" => 0, "message" => "Stock not found");
                        echo json_encode($response);
                        exit();
                    }
                    $stock->setQuantity($quantity);
                    $entityManager->flush();
                    echo json_encode($stock);
                    break;

                case 'updateEmployee':
                    try {
                        if (
                            !isset($_PUT['name']) || empty($_PUT['name']) ||
                            !isset($_PUT['email']) || empty($_PUT['email']) ||
                            !isset($_PUT['password']) || empty($_PUT['password']) ||
                            !isset($_PUT['role']) || empty($_PUT['role'])
                        ) {
                            $response = array("status" => 0, "message" => "All fields are required");
                            echo json_encode($response);
                            exit();
                        }
                        $employeeName = $_PUT['name'];
                        $employeeEmail = $_PUT['email'];
                        $employeePassword = $_PUT['password'];
                        $employeeRole = $_PUT['role'];

                        // Appel de la fonction updateEmployee
                        $response = $entityManager->getRepository(Employees::class)->updateEmployee($employeeName, $employeeEmail, $employeePassword, $employeeRole);
                        echo json_encode($response);
                    } catch (Exception $e) {
                        $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
                        echo json_encode($response);
                    }
                    break;

                case 'updateProduct':
                    if (
                        !isset($_PUT['id']) || empty($_PUT['id']) ||
                        !isset($_PUT['name']) || empty($_PUT['name']) ||
                        !isset($_PUT['brand']) || empty($_PUT['brand']) ||
                        !isset($_PUT['category']) || empty($_PUT['category']) ||
                        !isset($_PUT['price']) || empty($_PUT['price']) ||
                        !isset($_PUT['year']) || empty($_PUT['year'])
                    ) {
                        $response = array("status" => 0, "message" => "All fields are required");
                        echo json_encode($response);
                        exit();
                    }

                    $productId = $_PUT['id'];
                    $productName = $_PUT['name'];
                    $brandId = $_PUT['brand'];
                    $categoryId = $_PUT['category'];
                    $price = $_PUT['price'];
                    $year = $_PUT['year'];

                    // Assuming the updateProduct function is in the ProductRepository
                    $productRepository = $entityManager->getRepository(Products::class);
                    $result = $productRepository->updateProduct($productId, $productName, $brandId, $categoryId, $price, $year);

                    if (isset($result['status']) && $result['status'] == 0) {
                        echo json_encode($result);
                        exit();
                    }

                    echo json_encode($result);
                    break;

                case 'updateBrand':
                    parse_str(file_get_contents("php://input"), $_PUT);

                    if (!isset($_PUT['id']) || empty($_PUT['id']) || !isset($_PUT['name']) || empty($_PUT['name'])) {
                        $response = array("status" => 0, "message" => "Brand id and name are required");
                        echo json_encode($response);
                        exit();
                    }

                    $brandId = $_PUT['id'];
                    $brandName = $_PUT['name'];
                    $brandRepository = $entityManager->getRepository(Brands::class);
                    $result = $brandRepository->updateBrand($brandId, $brandName);

                    if (isset($result['status']) && $result['status'] == 0) {
                        echo json_encode($result);
                        exit();
                    }

                    echo json_encode($result);
                    break;

                case 'updateCategory':
                    parse_str(file_get_contents("php://input"), $_PUT);

                    if (
                        !isset($_PUT['name']) || empty($_PUT['name']) ||
                        !isset($_PUT['newName']) || empty($_PUT['newName'])
                    ) {
                        $response = array("status" => 0, "message" => "Category name and new name are required");
                        echo json_encode($response);
                        exit();
                    }

                    $categoryName = $_PUT['name'];
                    $newCategoryName = $_PUT['newName'];
                    $categoryRepository = $entityManager->getRepository(Categories::class);
                    $result = $categoryRepository->updateCategory($categoryName, $newCategoryName);

                    if (isset($result['status']) && $result['status'] == 0) {
                        echo json_encode($result);
                        exit();
                    }

                    echo json_encode($result);
                    break;

                case 'updateStore':
                    if (
                        !isset($_PUT['id']) || empty($_PUT['id']) ||
                        !isset($_PUT['name']) || empty($_PUT['name']) ||
                        !isset($_PUT['address']) || empty($_PUT['address'])
                    ) {
                        $response = array("status" => 0, "message" => "All fields are required");
                        echo json_encode($response);
                        exit();
                    }

                    $storeId = $_PUT['id'];
                    $storeName = $_PUT['name'];
                    $storeZip = $_PUT['zip'];
                    $storeCity = $_PUT['city'];
                    $storeStreet = $_PUT['street'];
                    $storeState = $_PUT['state'];

                    $storesRepository = $entityManager->getRepository(Stores::class);
                    $response = $storesRepository->updateStore($storeId, $storeName, $storeZip, $storeCity, $storeStreet, $storeState);

                    echo json_encode($response);
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
                    if (!isset($_DELETE['id']) || empty($_DELETE['id'])) {
                        $response = array("status" => 0, "message" => "Stock ID is required");
                        echo json_encode($response);
                        exit();
                    }

                    $stockId = $_DELETE['id'];
                    $stock = $entityManager->getRepository(Stocks::class)->find($stockId);
                    if (!$stock) {
                        $response = array("status" => 0, "message" => "Stock not found");
                        echo json_encode($response);
                        exit();
                    }
                    $entityManager->remove($stock);
                    $entityManager->flush();
                    echo json_encode(array("status" => 1, "message" => "Stock deleted successfully"));
                    break;

                case 'deleteEmployee':
                    try {
                        if (!isset($_DELETE['Name']) || empty($_DELETE['Name'])) {
                            $response = array("status" => 0, "message" => "Employee name is required");
                            echo json_encode($response);
                            exit();
                        }

                        $employeeName = $_DELETE['Name'];

                        // Appel de la fonction deleteEmployee
                        $response = $entityManager->getRepository(Employees::class)->deleteEmployee($employeeName);
                        echo json_encode($response);
                    } catch (Exception $e) {
                        $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
                        echo json_encode($response);
                    }
                    break;

                case 'deleteProduct':
                    parse_str(file_get_contents("php://input"), $_DELETE);
                    if (!isset($_DELETE['id']) || empty($_DELETE['id'])) {
                        $response = array("status" => 0, "message" => "Product ID is required");
                        echo json_encode($response);
                        exit();
                    }

                    $productId = $_DELETE['id'];

                    // Assuming the deleteProduct function is in the ProductRepository
                    $productRepository = $entityManager->getRepository(Products::class);
                    $result = $productRepository->deleteProduct($productId);

                    if (isset($result['status']) && $result['status'] == 0) {
                        echo json_encode($result);
                        exit();
                    }

                    echo json_encode($result);
                    break;

                case 'deleteBrand':
                    parse_str(file_get_contents("php://input"), $_DELETE);

                    if (!isset($_DELETE['id']) || empty($_DELETE['id'])) {
                        $response = array("status" => 0, "message" => "Brand id is required");
                        echo json_encode($response);
                        exit();
                    }

                    $brandId = $_DELETE['id'];
                    $brandRepository = $entityManager->getRepository(Brands::class);
                    $result = $brandRepository->deleteBrand($brandId);

                    echo json_encode($result);
                    break;

                case 'deleteCategory':
                    parse_str(file_get_contents("php://input"), $_DELETE);

                    if (!isset($_DELETE['name']) || empty($_DELETE['name'])) {
                        $response = array("status" => 0, "message" => "Category name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $categoryName = $_DELETE['name'];
                    $categoryRepository = $entityManager->getRepository(Categories::class);
                    $result = $categoryRepository->deleteCategory($categoryName);

                    echo json_encode($result);

                    break;
            }
        }
        break;
}
