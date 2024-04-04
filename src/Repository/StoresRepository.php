<?php
// src/Repository/StoresRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Stores;

class StoresRepository extends EntityRepository
{

    //méthodes get

    public function getStores()
    {
        $response = array();
        try {
            $stores = $this->findAll();
            $response = array("status" => 1, "data" => $stores);
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


    //méthodes delete


}
