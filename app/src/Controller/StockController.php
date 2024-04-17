<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Stocks;
use App\Entity\Stores;
use App\Entity\Products;
use App\Repository\StockRepository;

/**
 * Class StockController
 * @package App\Controller
 */
class StockController
{
    /** @var StockRepository */
    private $stockRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var string */
    const API_KEY = 'e8f1997c763';

    /**
     * StockController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->stockRepository = $entityManager->getRepository(Stocks::class);
    }

    /**
     * Update stock information
     *
     * @OA\Put(
     *     path="/bikestores/stocks/update/{stockId}",
     *     tags={"Stocks"},
     *     summary="Update stock information",
     *     operationId="updateStock",
     *     @OA\Parameter(
     *         name="stockId",
     *         in="path",
     *         required=true,
     *         description="ID of the stock to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"storeId", "productId", "quantity"},
     *             @OA\Property(property="storeId", type="integer"),
     *             @OA\Property(property="productId", type="integer"),
     *             @OA\Property(property="quantity", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stock updated",
     *         @OA\JsonContent(ref="#/components/schemas/Stock")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
     */
    public function updateStock($params)
    {
        $stockId = $params['stockId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $stock = $this->entityManager->getRepository(Stocks::class)->find($stockId);

            if (!$stock) {
                echo json_encode(["error" => "Stock not found"]);
                return;
            }

            if (isset($_PUT['storeId'])) {
                $storeId = $_PUT['storeId'];
                $store = $this->entityManager->getRepository(Stores::class)->find($storeId);
                if (!$store) {
                    echo json_encode(["error" => "Store not found"]);
                    return;
                }
            }

            if (isset($_PUT['productId'])) {
                $productId = $_PUT['productId'];
                $product = $this->entityManager->getRepository(Products::class)->find($productId);
                if (!$product) {
                    echo json_encode(["error" => "Product not found"]);
                    return;
                }
            }

            $quantity = $_PUT['quantity'];
            $stock->setQuantity($quantity);

            $this->entityManager->flush();

            echo json_encode(['success' => 'Stock updated']);
            return $stock;
        } else {
            echo json_encode(["error" => "Invalid request"]);
        }
    }
}
