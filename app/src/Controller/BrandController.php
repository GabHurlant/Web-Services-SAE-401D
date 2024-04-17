<?php

namespace App\Controller;

use App\Entity\Brands;

/**
 * Class BrandController
 * @package App\Controller
 */
class BrandController
{
    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $brandRepository;

    /** @var string */
    const API_KEY = "e8f1997c763";

    /**
     * BrandController constructor.
     * @param mixed $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get all brands
     * @OA\Get(
     *     path="/bikestores/brands",
     *     @OA\Response(response="200", description="Returns all brands")
     * )
     */

    public function getAllBrands()
    {
        $brands = $this->entityManager->getRepository(Brands::class)->findAll();

        echo json_encode($brands);
    }


    /**
     * Find products by brand name
     * @param string $brandName
     * * @OA\Get(
     *     path="/bikestores/brands/{brandName}",
     *     @OA\Parameter(
     *         name="brandName",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Returns products by brand name")
     * )
     */
    public function findProductsByBrandName($brandName)
    {
        $this->brandRepository = $this->entityManager->getRepository(Brands::class);
        $brand = $this->brandRepository->findOneBy(['brand_name' => $brandName]);
        if ($brand) {
            $products = $brand->getProducts()->toArray();
            $productData = [];
            foreach ($products as $product) {
                $productData[] = [
                    'id' => $product->getProductId(),
                    'name' => $product->getProductName(),
                    'brand' => $product->getBrand()->getBrandName(),
                    'category' => $product->getCategory()->getCategoryName(),
                    'year' => $product->getModelYear(),
                    'price' => $product->getListPrice()
                ];
            }

            echo json_encode($productData);
        } else {

            echo json_encode(["error" => "Brand not found"]);
        }
    }

    // method post

    /**
     * Create a new brand
     * @param string $brandName
     * @return Brands|null
     * @OA\Post(
     *     path="/bikestores/brands/create",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="brandName", type="string"),
     *                 @OA\Property(property="API_KEY", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create a new brand")
     * )
     */
    public function createBrand($brandName)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['brandName'])) {
            $brandName = $_POST['brandName'];

            if (!isset($_POST["API_KEY"]) || $_POST['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $brand = new Brands();
            $brand->setBrandName($brandName);

            $this->entityManager->persist($brand);
            $this->entityManager->flush();

            echo json_encode(["success" => "Brand created successfully"]);

            return $brand;
        } else {
            echo json_encode(["error" => "invalid request"]);
        }
    }

    // method put
    /**
     * Update an existing brand
     * @param array $params
     * @return Brands|null
     *  @OA\Put(
     *     path="/bikestores/brands/update/{brandId}",
     *     @OA\Parameter(
     *         name="brandId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="brandName", type="string"),
     *                 @OA\Property(property="API_KEY", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update an existing brand")
     * )
     */
    public function updateBrand($params)
    {
        $brandId = $params['brandId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            if (isset($_PUT['brandName'])) {
                $brandName = $_PUT['brandName'];

                $brand = $this->entityManager->getRepository(Brands::class)->find($brandId);

                $brand->setBrandName($brandName);

                $this->entityManager->flush();

                echo json_encode(['success' => 'Brand updated']);
                return $brand;
            } else {
                echo json_encode(["error" => "Brand name required"]);
            }
        } else {
            echo json_encode(["error" => "invalid request"]);
        }
    }

    // method delete

    /**
     * Delete a brand
     * @param array $params
     * @OA\Delete(
     *     path="/bikestores/brands/delete/{brandId}",
     *     @OA\Parameter(
     *         name="brandId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Delete a brand")
     * )
     */
    public function deleteBrand($params)
    {
        $brandId = $params['brandId'];
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            if (!isset($_POST["API_KEY"]) || $_POST['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $brand = $this->entityManager->getRepository(Brands::class)->find($brandId);

            $this->entityManager->remove($brand);
            $this->entityManager->flush();

            echo json_encode(['success' => 'Brand deleted']);
        } else {
            echo json_encode(["error" => "invalid request"]);
            return;
        }
    }
}
