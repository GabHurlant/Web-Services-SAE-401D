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
                    $brandName = $_GET['name'];
                    $brandRepository = $entityManager->getRepository(Brands::class);
                    $newBrand = $brandRepository->insertNewBrand($brandName);
                    echo json_encode($newBrand);
                    break;

                case 'addCategory':
                    try {
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
                        echo json_encode($response);
                    } catch (Exception $e) {
                        $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
                        echo json_encode($response);
                    }
                    break;

                case 'addProduct':
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

                        $store = $entityManager->getRepository(Stores::class)->find($storeId);

                        // Vérifiez si l'employé existe déjà
                        $existingEmployee = $entityManager->getRepository(Employees::class)->findOneBy(['employeeEmail' => $employeeEmail]);
                        if ($existingEmployee) {
                            $response = array("status" => 0, "message" => "Employee already in the database");
                            echo json_encode($response);
                            exit();
                        }

                        $employee = new Employees();
                        $employee->setStore($store);
                        $employee->setEmployeeName($employeeName);
                        $employee->setEmployeeEmail($employeeEmail);
                        $employee->setEmployeePassword($employeePassword);
                        $employee->setEmployeeRole($employeeRole);

                        $entityManager->persist($employee);
                        $entityManager->flush();

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
                    $employeeName = $_PUT['name'];
                    $employeeEmail = $_PUT['email'];
                    $employeePassword = $_PUT['password'];
                    $employeeRole = $_PUT['role'];

                    $employee = $entityManager->getRepository(Employees::class)->findOneBy($employeeName);
                    if (!$employee) {
                        $response = array("status" => 0, "message" => "Employee not found");
                        echo json_encode($response);
                        exit();
                    }
                    $employee->setEmployeeName($employeeName);
                    $employee->setEmployeeEmail($employeeEmail);
                    $employee->setEmployeePassword($employeePassword);
                    $employee->setEmployeeRole($employeeRole);

                    $entityManager->flush();

                    echo json_encode($employee);
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

                    $brand = $entityManager->getRepository(Brands::class)->find($brandId);
                    if (!$brand) {
                        $response = array("status" => 0, "message" => "Brand not found");
                        echo json_encode($response);
                        exit();
                    }

                    $category = $entityManager->getRepository(Categories::class)->find($categoryId);
                    if (!$category) {
                        $response = array("status" => 0, "message" => "Category not found");
                        echo json_encode($response);
                        exit();
                    }

                    $product = $entityManager->getRepository(Products::class)->find($productId);
                    if (!$product) {
                        $response = array("status" => 0, "message" => "Product not found");
                        echo json_encode($response);
                        exit();
                    }

                    $product->setProductName($productName);
                    $product->setBrand($brand);
                    $product->setCategory($category);
                    $product->setListPrice($price);
                    $product->setModelYear($year);
                    $entityManager->flush();
                    echo json_encode($product);
                    break;

                case 'updateBrand':
                    if (!isset($_PUT['name']) || empty($_PUT['name'])) {
                        $response = array("status" => 0, "message" => "Brand name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $brandName = $_PUT['name'];
                    $brand = $entityManager->getRepository(Brands::class)->findOneBy(['brandName' => $brandName]);
                    if (!$brand) {
                        $response = array("status" => 0, "message" => "Brand not found");
                        echo json_encode($response);
                        exit();
                    }
                    $brand->setBrandName($brandName);
                    $entityManager->flush();
                    echo json_encode($brand);
                    break;

                case 'updateCategory':
                    if (!isset($_PUT['name']) || empty($_PUT['name'])) {
                        $response = array("status" => 0, "message" => "Category name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $categoryName = $_PUT['name'];
                    $category = $entityManager->getRepository(Categories::class)->findOneBy(['categoryName' => $categoryName]);
                    if (!$category) {
                        $response = array("status" => 0, "message" => "Category not found");
                        echo json_encode($response);
                        exit();
                    }
                    $category->setCategoryName($categoryName);
                    $entityManager->flush();
                    echo json_encode($category);
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
                    $storeAddress = $_PUT['address'];
                    $store = $entityManager->getRepository(Stores::class)->find($storeId);
                    if (!$store) {
                        $response = array("status" => 0, "message" => "Store not found");
                        echo json_encode($response);
                        exit();
                    }
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
                    if (!isset($_DELETE['Name']) || empty($_DELETE['Name'])) {
                        $response = array("status" => 0, "message" => "Employee name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $employeeName = $_DELETE['Name'];
                    $employee = $entityManager->getRepository(Employees::class)->findOneBy(['name' => $employeeName]);
                    if (!$employee) {
                        $response = array("status" => 0, "message" => "Employee not found");
                        echo json_encode($response);
                        exit();
                    }
                    $entityManager->remove($employee);
                    $entityManager->flush();
                    echo json_encode(array("status" => 1, "message" => "Employee deleted successfully"));
                    break;

                case 'deleteProduct':
                    if (!isset($_DELETE['name']) || empty($_DELETE['name'])) {
                        $response = array("status" => 0, "message" => "Product name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $productName = $_DELETE['name'];
                    $product = $entityManager->getRepository(Products::class)->findOneBy(['productName' => $productName]);
                    if (!$product) {
                        $response = array("status" => 0, "message" => "Product not found");
                        echo json_encode($response);
                        exit();
                    }
                    $entityManager->remove($product);
                    $entityManager->flush();
                    echo json_encode(array("status" => 1, "message" => "Product deleted successfully"));
                    break;

                case 'deleteBrand':
                    if (!isset($_DELETE['name']) || empty($_DELETE['name'])) {
                        $response = array("status" => 0, "message" => "Brand name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $brandName = $_DELETE['name'];
                    $brand = $entityManager->getRepository(Brands::class)->findOneBy(['brandName' => $brandName]);
                    if (!$brand) {
                        $response = array("status" => 0, "message" => "Brand not found");
                        echo json_encode($response);
                        exit();
                    }
                    $entityManager->remove($brand);
                    $entityManager->flush();
                    echo json_encode(array("status" => 1, "message" => "Brand deleted successfully"));
                    break;

                case 'deleteCategory':
                    if (!isset($_DELETE['name']) || empty($_DELETE['name'])) {
                        $response = array("status" => 0, "message" => "Category name is required");
                        echo json_encode($response);
                        exit();
                    }

                    $categoryName = $_DELETE['name'];
                    $category = $entityManager->getRepository(Categories::class)->findOneBy(['categoryName' => $categoryName]);
                    if (!$category) {
                        $response = array("status" => 0, "message" => "Category not found");
                        echo json_encode($response);
                        exit();
                    }
                    $entityManager->remove($category);
                    $entityManager->flush();
                    echo json_encode(array("status" => 1, "message" => "Category deleted successfully"));
                    break;
            }
        }
        break;
}
