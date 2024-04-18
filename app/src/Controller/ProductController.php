<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Products;
use App\Entity\Brands;
use App\Entity\Categories;
use App\Repository\ProductRepository;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var EntityManager */
    private $entityManager;

    const API_KEY = 'e8f1997c763';

    /**
     * ProductController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $entityManager->getRepository(Products::class);
    }

    /**
     * Retrieve all products
     */
    public function getAllProducts()
    {
        $products = $this->productRepository->findAll();
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    /**
     * Retrieve a product by ID
     * @param array $productId
     */
    public function getProduct($productId)
    {
        $productId = $productId['productId'];
        $product = $this->productRepository->find($productId);
        header('Content-Type: application/json');
        echo json_encode($product);
    }

    /**
     * Create a new product
     */
    public function createProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productName']) && isset($_POST['brandId']) && isset($_POST['categoryId']) && isset($_POST['modelYear']) && isset($_POST['productPrice'])) {

            if (!isset($_PUT["API_KEY"]) || $_POST['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $productName = $_POST['productName'];
            $brandId = $_POST['brandId'];
            $categoryId = $_POST['categoryId'];
            $modelYear = $_POST['modelYear'];
            $productPrice = $_POST['productPrice'];

            $brand = $this->entityManager->getRepository(Brands::class)->find($brandId);
            $category = $this->entityManager->getRepository(Categories::class)->find($categoryId);

            $product = new Products();
            $product->setProductName($productName);
            $product->setBrand($brand);
            $product->setCategory($category);
            $product->setModelYear($modelYear);
            $product->setListPrice($productPrice);

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            echo json_encode(["success" => "Product created"]);
        } else {

            echo json_encode(["error" => "Product not created"]);
        }
    }

    /**
     * Update a product
     * @param array $params
     * @return mixed
     */
    public function updateProduct($params)
    {
        $productId = $params['productId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key or API Key missing"]);
                return;
            }

            $product = $this->entityManager->getRepository(Products::class)->find($productId);

            if (isset($_PUT['productName'])) {
                $productName = $_PUT['productName'];
                $product->setProductName($productName);
            }

            if (isset($_PUT['brandId'])) {
                $brandId = $_PUT['brandId'];
                $brand = $this->entityManager->getRepository(Brands::class)->find($brandId);
                if (!$brand) {
                    echo json_encode(["error" => "Brand not found"]);
                    return;
                }
                $product->setBrand($brand);
            }

            if (isset($_PUT['categoryId'])) {
                $categoryId = $_PUT['categoryId'];
                $category = $this->entityManager->getRepository(Categories::class)->find($categoryId);
                if (!$category) {
                    echo json_encode(["error" => "Category not found"]);
                    return;
                }
                $product->setCategory($category);
            }

            if (isset($_PUT['productPrice'])) {
                $productPrice = $_PUT['productPrice'];
                $product->setListPrice($productPrice);
            }

            if (isset($_PUT['modelYear'])) {
                $modelYear = $_PUT['modelYear'];
                $product->setModelYear($modelYear);
            }

            $this->entityManager->flush();

            echo json_encode(['success' => 'Product updated']);
            return $product;
        } else {
            echo json_encode(["error" => "invalid request"]);
        }
    }

    /**
     * Delete a product
     * @param array $params
     */
    public function deleteProduct($params)
    {
        $productId = $params['productId'];
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            parse_str(file_get_contents('php://input'), $_DELETE);

            if (!isset($_DELETE["API_KEY"]) || $_DELETE['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $product = $this->entityManager->getRepository(Products::class)->find($productId);

            if (!$product) {
                echo json_encode(["error" => "Product not found"]);
                return;
            }

            $this->entityManager->remove($product);
            $this->entityManager->flush();

            echo json_encode(['success' => 'Product deleted']);
        } else {
            echo json_encode(["error" => "invalid request"]);
            return;
        }
        https: //chat.openai.com/c/2ef03c5c-60b7-40b2-b50c-adcdd8bf9f18
    }
}
