<?php

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
        'path' => '/brands/(?P<brandName>\w+)',
        'controller' => $brandController,
        'action' => 'findProductsByBrandName'
    ],
    [
        'method' => 'POST',
        'path' => '/brands',
        'controller' => $brandController,
        'action' => 'createBrand'
    ],
    [
        'method' => 'PUT',
        'path' => '/brands/update/(?P<brandId>\d+)',
        'controller' => $brandController,
        'action' => 'updateBrand'
    ],
    [
        'method' => 'DELETE',
        'path' => '/brands/delete/(?P<brandId>\d+)',
        'controller' => $brandController,
        'action' => 'deleteBrand'
    ],
];
