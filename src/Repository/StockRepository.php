<?php
// src/Repository/StockRepository.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Stocks;
use Entity\Stores;
use Entity\Products;

class StockRepository extends EntityRepository
{

    //méthodes get

    //méthodes post

    public function addStock($storeId, $productId, $quantity)
    {
        $response = array();
        try {
            $store = $this->_em->getRepository(Stores::class)->find($storeId);
            $product = $this->_em->getRepository(Products::class)->find($productId);

            // Vérifiez si le stock existe déjà
            $existingStock = $this->_em->getRepository(Stocks::class)->findOneBy(['store' => $store, 'product' => $product]);
            if ($existingStock) {
                $response = array("status" => 0, "message" => "Stock already in the database");
                return $response;
            }

            $stock = new Stocks();
            $stock->setStore($store);
            $stock->setProduct($product);
            $stock->setQuantity($quantity);
            $this->_em->persist($stock);
            $this->_em->flush();

            $response = array("status" => 1, "message" => "Stock added successfully", "data" => $stock);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }
    //méthodes put

    function updateStock($stockId, $quantity)
    {
        try {
            $stock = $this->_em->getRepository(Stocks::class)->find($stockId);
            if (!$stock) {
                return array("status" => 0, "message" => "Stock not found");
            }
            $stock->setQuantity($quantity);
            $this->_em->flush();
            return $stock;
        } catch (\Exception $e) {
            return array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
    }

    //méthodes delete

    function deleteStock($stockId)
    {
        try {
            $stock = $this->_em->getRepository(Stocks::class)->find($stockId);
            if (!$stock) {
                return array("status" => 0, "message" => "Stock not found");
            }
            $this->_em->remove($stock);
            $this->_em->flush();
            return array("status" => 1, "message" => "Stock deleted successfully");
        } catch (\Exception $e) {
            return array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
    }
}
