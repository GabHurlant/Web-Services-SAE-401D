<?php
// src/Repository/BrandRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Brands;

class BrandRepository extends EntityRepository
{

    //méthodes get

    public function getAllBrands()
    {
        $response = array();
        try {
            $brands = $this->_em->getRepository(Brands::class)->findAll();
            $response = array("status" => 1, "message" => "Brands found", "data" => $brands);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    public function findProductsByBrandName($brandName)
    {
        $response = array();
        try {
            $brand = $this->findOneBy(['brand_name' => $brandName]);
            if ($brand) {
                $products = $brand->getProducts()->toArray();
                $response = array("status" => 1, "message" => "Products found", "data" => $products);
            } else {
                $response = array("status" => 0, "message" => "Brand not found");
            }
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
        return $response;
    }

    //méthodes post
    public function insertNewBrand($brandName)
    {
        $response = array();

        try {
            if (empty($brandName)) {
                $response = array("status" => 0, "message" => "Brand's name required");
                return $response;
            }
            $existingBrand = $this->_em->getRepository(Brands::class)->findOneBy(['brand_name' => $brandName]);
            if ($existingBrand) {
                $response = array("status" => 0, "message" => "Brand already in the database");
                return $response;
            }
            $brand = new Brands();
            $brand->setBrandName($brandName);
            $this->_em->persist($brand);
            $this->_em->flush();
            $response = array("status" => 1, "message" => "Brand added successfully", "data" => $brand);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    //méthodes put
    public function updateBrand($brandId, $brandName)
    {
        try {
            $brand = $this->find($brandId);
            if (!$brand) {
                return array("status" => 0, "message" => "Brand not found");
            }
            $brand->setBrandName($brandName);
            $this->_em->flush();
            return $brand;
        } catch (\Exception $e) {
            return array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
    }

    //méthodes delete

    public function deleteBrand($brandId)
    {
        try {
            $brand = $this->find($brandId);
            if (!$brand) {
                return array("status" => 0, "message" => "Brand not found");
            }
            $this->_em->remove($brand);
            $this->_em->flush();
            return array("status" => 1, "message" => "Brand deleted successfully");
        } catch (\Exception $e) {
            return array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
    }
}
