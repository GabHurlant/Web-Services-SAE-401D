<?php

require_once __DIR__ . "/../bootstrap.php";

use App\Controller\BrandController;
use App\Controller\CategoriesController;
use App\Controller\EmployeeController;
use App\Controller\ProductController;
use App\Controller\StockController;
use App\Controller\StoreController;

$brandController = new BrandController($entityManager);
$categoriesController = new CategoriesController($entityManager);
$employeeController = new EmployeeController($entityManager);
$productController = new ProductController($entityManager);
$stockController = new StockController($entityManager);
$storeController = new StoreController($entityManager);

return [

    //class brand
    [
        'method' => 'GET',
        'path' => '/bikestores/brands',
        'controller' => $brandController,
        'action' => 'getAllBrands'
    ],
    [
        'method' => 'GET',
        'path' => '/bikestores/brands/(?P<brandName>\w+)',
        'controller' => $brandController,
        'action' => 'findProductsByBrandName'
    ],
    [
        'method' => 'POST',
        'path' => '/bikestores/brands/create',
        'controller' => $brandController,
        'action' => 'createBrand'
    ],
    [
        'method' => 'PUT',
        'path' => '/bikestores/brands/update/(?P<brandId>\d+)',
        'controller' => $brandController,
        'action' => 'updateBrand'
    ],
    [
        'method' => 'DELETE',
        'path' => '/bikestores/brands/delete/(?P<brandId>\d+)',
        'controller' => $brandController,
        'action' => 'deleteBrand'
    ],

    //class Categories
    [
        'method' => 'GET',
        'path' => '/bikestores/categories/(?P<categoryId>\d+)',
        'controller' => $categoriesController,
        'action' => 'getCategory'
    ],
    [
        'method' => 'GET',
        'path' => '/bikestores/categories',
        'controller' => $categoriesController,
        'action' => 'getAllCategories'
    ],
    [
        'method' => 'GET',
        'path' => '/bikestores/categories/(?P<categoryName>[a-zA-Z0-9\s%]+)',
        'controller' => $categoriesController,
        'action' => 'findProductsByCategoryName'
    ],
    [
        'method' => 'POST',
        'path' => '/bikestores/categories/create',
        'controller' => $categoriesController,
        'action' => 'createCategory'
    ],
    [
        'method' => 'PUT',
        'path' => '/bikestores/categories/update/(?P<categoryId>\d+)',
        'controller' => $categoriesController,
        'action' => 'updateCategory'
    ],
    [
        'method' => 'DELETE',
        'path' => '/bikestores/categories/delete/(?P<categoryId>\d+)',
        'controller' => $categoriesController,
        'action' => 'deleteCategory'
    ],

    //class Employee
    [
        'method' => 'GET',
        'path' => '/bikestores/employees',
        'controller' => $employeeController,
        'action' => 'getAllEmployees'
    ],
    [
        'method' => 'POST',
        'path' => '/bikestores/employees/create',
        'controller' => $employeeController,
        'action' => 'addEmployee'
    ],
    [
        'method' => 'PUT',
        'path' => '/bikestores/employees/update/(?P<employeeId>\d+)',
        'controller' => $employeeController,
        'action' => 'updateEmployee'
    ],
    [
        'method' => 'DELETE',
        'path' => '/bikestores/employees/delete/(?P<employeeId>\d+)',
        'controller' => $employeeController,
        'action' => 'deleteEmployee'
    ],

    //class products

    [
        'method' => 'GET',
        'path' => '/bikestores/products',
        'controller' => $productController,
        'action' => 'getAllProducts'
    ],
    [
        'method' => 'GET',
        'path' => '/bikestores/products/(?P<productId>\d+)',
        'controller' => $productController,
        'action' => 'getProduct'
    ],
    [
        'method' => 'POST',
        'path' => '/bikestores/products/create',
        'controller' => $productController,
        'action' => 'createProduct'
    ],
    [
        'method' => 'PUT',
        'path' => '/bikestores/products/update/(?P<productId>\d+)',
        'controller' => $productController,
        'action' => 'updateProduct'
    ],
    [
        'method' => 'DELETE',
        'path' => '/bikestores/products/delete/(?P<productId>\d+)',
        'controller' => $productController,
        'action' => 'deleteProduct'
    ],

    //class stock
    [
        'method' => 'PUT',
        'path' => '/bikestores/stocks/update/(?P<stockId>\d+)',
        'controller' => $stockController,
        'action' => 'updateStock'
    ],

    //class stores
    [
        'method' => 'GET',
        'path' => '/bikestores/stores',
        'controller' => $storeController,
        'action' => 'getStores'
    ],
    [
        'method' => 'GET',
        'path' => '/bikestores/stores/employees/(?P<storeName>[a-zA-Z0-9_\-]+)',
        'controller' => $storeController,
        'action' => 'getEmployeesByStoreId'
    ],
    [
        'method' => 'POST',
        'path' => '/bikestores/stores/create',
        'controller' => $storeController,
        'action' => 'createStore'
    ],
    [
        'method' => 'PUT',
        'path' => '/bikestores/stores/update/(?P<storeId>\d+)',
        'controller' => $storeController,
        'action' => 'updateStore'
    ],

];
