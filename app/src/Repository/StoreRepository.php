<?php
// src/Repository/StoreRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Stores;

class StoreRepository extends EntityRepository
{

    //méthodes get

    public function getStores()
    {
        $response = array();
        try {
            $employees = $this->_em->getRepository(Stores::class)->findAll();
            $response = array("status" => 1, "message" => "Employees found", "data" => $employees);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    public function getEmployeesByStoreName($storeName)
    {
        $response = array();
        try {
            $store = $this->_em->getRepository(Stores::class)->find($storeName);
            $employees = $store ? $store->getEmployees()->toArray() : [];

            $response = array("status" => 1, "data" => $employees);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    //méthodes post


    //méthodes put

    public function updateStore($storeId, $storeName, $storeZip, $storeCity, $storeStreet, $storeState)
    {
        $response = array();
        try {
            $store = $this->_em->getRepository(Stores::class)->find($storeId);
            if (!$store) {
                $response = array("status" => 0, "message" => "Store not found");
                return $response;
            }
            $store->setStoreName($storeName);
            $store->setZipCode($storeZip);
            $store->setCity($storeCity);
            $store->setStreet($storeStreet);
            $store->setState($storeState);
            $this->_em->flush();

            $response = array("status" => 1, "message" => "Store updated successfully", "data" => $store);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }
    //méthodes delete


}
