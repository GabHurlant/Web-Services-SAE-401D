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
    /**
     * @var StockRepository
     */
    private $stockRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * API Key
     */
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
     * Update stock
     * @param array $params
     * @return Stocks|null
     */
    public function updateStock($params)
    {
        $stockId = $params['stockId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return null;
            }

            $stock = $this->entityManager->getRepository(Stocks::class)->find($stockId);

            if (!$stock) {
                echo json_encode(["error" => "Stock not found"]);
                return null;
            }

            if (isset($_PUT['storeId'])) {
                $storeId = $_PUT['storeId'];
                $store = $this->entityManager->getRepository(Stores::class)->find($storeId);
                if (!$store) {
                    echo json_encode(["error" => "Store not found"]);
                    return null;
                }
            }

            if (isset($_PUT['productId'])) {
                $productId = $_PUT['productId'];
                $product = $this->entityManager->getRepository(Products::class)->find($productId);
                if (!$product) {
                    echo json_encode(["error" => "Product not found"]);
                    return null;
                }
            }

            $quantity = $_PUT['quantity'];
            $stock->setQuantity($quantity);

            $this->entityManager->flush();

            echo json_encode(['success' => 'Stock updated']);
            return $stock;
        } else {
            echo json_encode(["error" => "invalid request"]);
            return null;
        }
    }
}
