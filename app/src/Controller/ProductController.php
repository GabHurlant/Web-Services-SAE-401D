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

    /** @var string */
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
     * Get all products
     *
     * @OA\Get(
     *     path="/bikestores/products",
     *     tags={"Products"},
     *     summary="Get all products",
     *     operationId="getAllProducts",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Products not found"
     *     )
     * )
     */
    public function getAllProducts()
    {
        $products = $this->productRepository->findAll();

        echo json_encode($products);
    }

    /**
     * Get a product by ID
     *
     * @OA\Get(
     *     path="/bikestores/products/{productId}",
     *     tags={"Products"},
     *     summary="Get a product by ID",
     *     operationId="getProduct",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID of the product to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function getProduct($productId)
    {
        $productId = $productId['productId'];
        $product = $this->productRepository->find($productId);

        echo json_encode($product);
    }


    /**
     * Create a new product
     *
     * @OA\Post(
     *     path="/bikestores/products/create",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     operationId="createProduct",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"productName", "brandId", "categoryId", "modelYear", "productPrice"},
     *             @OA\Property(property="productName", type="string"),
     *             @OA\Property(property="brandId", type="integer"),
     *             @OA\Property(property="categoryId", type="integer"),
     *             @OA\Property(property="modelYear", type="integer"),
     *             @OA\Property(property="productPrice", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
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
     * Update an existing product
     *
     * @OA\Put(
     *     path="/bikestores/products/update/{productId}",
     *     tags={"Products"},
     *     summary="Update an existing product",
     *     operationId="updateProduct",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="productName", type="string"),
     *             @OA\Property(property="brandId", type="integer"),
     *             @OA\Property(property="categoryId", type="integer"),
     *             @OA\Property(property="modelYear", type="integer"),
     *             @OA\Property(property="productPrice", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
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
     *
     * @OA\Delete(
     *     path="/bikestores/products/delete/{productId}",
     *     tags={"Products"},
     *     summary="Delete an existing product",
     *     operationId="deleteProduct",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key"
     *     )
     * )
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
    }
}
