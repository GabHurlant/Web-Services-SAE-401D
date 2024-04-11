<?php

require_once 'vendor/autoload.php';

use Controller\StoreController;
use Controller\StockController;
use Controller\EmployeeController;
use Controller\ProductController;
use Controller\CategoriesController; // Change this line
use Controller\BrandController;

use Doctrine\ORM\EntityManager;

// Créez une instance de EntityManager
$entityManager = EntityManager::create($dbParams, $config);

// Créez une instance de votre contrôleur
$storeController = new StoreController($entityManager);
$stockController = new StockController($entityManager);
$employeeController = new EmployeeController($entityManager);
$productController = new ProductController($entityManager);
$categoryController = new CategoriesController($entityManager); // And this line
$brandController = new BrandController($entityManager);

// Obtenez le chemin à partir de l'URL
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route vers la bonne méthode en fonction du chemin
switch ($path) {
    case '/stores':
        $data = $storeController->getStores();
        break;
    case '/stores/employees':
        $storeName = $_GET['storeName'];
        $data = $storeController->getEmployeesByStoreName($storeName);
        break;
    case '/stores/update':
        $storeId = $_POST['storeId'];
        $storeName = $_POST['storeName'];
        $storeZip = $_POST['storeZip'];
        $storeCity = $_POST['storeCity'];
        $storeStreet = $_POST['storeStreet'];
        $storeState = $_POST['storeState'];
        $data = $storeController->updateStore($storeId, $storeName, $storeZip, $storeCity, $storeStreet, $storeState);
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        exit;
}

// Affichez les données en format JSON
header('Content-Type: application/json');
echo json_encode($data);
