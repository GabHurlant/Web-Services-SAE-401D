<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Stores;
use Repository\StoreRepository;

class StoreController
{
    private $storeRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->storeRepository = $entityManager->getRepository(Stores::class);
    }

    public function getStores()
    {
        $stores = $this->storeRepository->getStores();
        if ($stores['status'] == 0) {
            throw new \Exception($stores['message']);
        }
        return $stores['data'];
    }

    public function getEmployeesByStoreName($storeName)
    {
        $employees = $this->storeRepository->getEmployeesByStoreName($storeName);
        if ($employees['status'] == 0) {
            throw new \Exception($employees['message']);
        }
        return $employees['data'];
    }

    public function updateStore($storeId, $storeName, $storeZip, $storeCity, $storeStreet, $storeState)
    {
        $store = $this->storeRepository->updateStore($storeId, $storeName, $storeZip, $storeCity, $storeStreet, $storeState);
        if ($store['status'] == 0) {
            throw new \Exception($store['message']);
        }
        return $store['data'];
    }
}
