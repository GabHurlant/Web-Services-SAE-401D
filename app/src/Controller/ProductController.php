<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Products;
use Repository\ProductRepository;

class ProductController
{
    private $productRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->productRepository = $entityManager->getRepository(Products::class);
    }

    public function getAllProducts()
    {
        $products = $this->productRepository->getAllProducts();
        if ($products['status'] == 0) {
            throw new \Exception($products['message']);
        }
        return $products['data'];
    }

    public function getProductById($id)
    {
        $product = $this->productRepository->getProductById($id);
        if ($product['status'] == 0) {
            throw new \Exception($product['message']);
        }
        return $product['data'];
    }

    public function insertNewProduct($productName, $modelYear, $listPrice)
    {
        $product = $this->productRepository->insertNewProduct($productName, $modelYear, $listPrice);
        if ($product['status'] == 0) {
            throw new \Exception($product['message']);
        }
        return $product['data'];
    }

    public function updateProduct($productId, $productName, $brandId, $categoryId, $price, $year)
    {
        $product = $this->productRepository->updateProduct($productId, $productName, $brandId, $categoryId, $price, $year);
        if ($product['status'] == 0) {
            throw new \Exception($product['message']);
        }
        return $product['data'];
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->deleteProduct($id);
        if ($product['status'] == 0) {
            throw new \Exception($product['message']);
        }
        return $product['data'];
    }
}
