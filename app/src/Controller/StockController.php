<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Stocks;
use Repository\StockRepository;

class StockController
{
    private $stockRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->stockRepository = $entityManager->getRepository(Stocks::class);
    }

    public function addStock($storeId, $productId, $quantity)
    {
        $stock = $this->stockRepository->addStock($storeId, $productId, $quantity);
        if ($stock['status'] == 0) {
            throw new \Exception($stock['message']);
        }
        return $stock['data'];
    }

    public function updateStock($stockId, $quantity)
    {
        $stock = $this->stockRepository->updateStock($stockId, $quantity);
        if ($stock['status'] == 0) {
            throw new \Exception($stock['message']);
        }
        return $stock['data'];
    }

    public function deleteStock($stockId)
    {
        $stock = $this->stockRepository->deleteStock($stockId);
        if ($stock['status'] == 0) {
            throw new \Exception($stock['message']);
        }
        return $stock['data'];
    }
}
