<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Brands;

class BrandController
{
    private $entityManager;
    private $brandRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->brandRepository = $entityManager->getRepository(Brands::class);
    }

    public function getBrand($brandId)
    {
        $brand = $this->brandRepository->find($brandId);
        if ($brand['status'] == 0) {
            throw new \Exception($brand['message']);
        }
        return $brand['data'];
    }

    public function getAllBrands()
    {
        $brands = $this->brandRepository->getAllBrands();
        if ($brands['status'] == 0) {
            throw new \Exception($brands['message']);
        }
        return $brands['data'];
    }

    public function findProductsByBrandName($brandName)
    {
        $brand = $this->brandRepository->findProductsByBrandName($brandName);
        if ($brand['status'] == 0) {
            throw new \Exception($brand['message']);
        }
        return $brand['data'];
    }

    public function createBrand($brandName)
    {
        $brand = $this->brandRepository->insertNewBrand($brandName);
        if ($brand['status'] == 0) {
            throw new \Exception($brand['message']);
        }
        return $brand['data'];
    }

    public function updateBrand($brandId, $newBrandName)
    {
        $brand = $this->brandRepository->updateBrand($brandId, $newBrandName);
        if ($brand['status'] == 0) {
            throw new \Exception($brand['message']);
        }
        return $brand;
    }

    public function deleteBrand($brandId)
    {
        $brand = $this->brandRepository->deleteBrand($brandId);
        if ($brand['status'] == 0) {
            throw new \Exception($brand['message']);
        }
        return $brand;
    }
}
