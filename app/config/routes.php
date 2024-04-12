<?php
require_once __DIR__ . "/../Router.php";

use Controller\BrandController;

$brandController = new BrandController($entityManager);

return [

    //class brand
    [
        'method' => 'GET',
        'path' => '/brands',
        'controller' => $brandController,
        'action' => 'getAllBrands'
    ],
    [
        'method' => 'GET',
        'path' => '/brands/{id}',
        'controller' => $brandController,
        'action' => 'getBrand'
    ],
    [
        'method' => 'POST',
        'path' => '/brands',
        'controller' => $brandController,
        'action' => 'createBrand'
    ],
    [
        'method' => 'PUT',
        'path' => '/brands/{id}',
        'controller' => $brandController,
        'action' => 'updateBrand'
    ],
    [
        'method' => 'DELETE',
        'path' => '/brands/{id}',
        'controller' => $brandController,
        'action' => 'deleteBrand',
    ]
];
