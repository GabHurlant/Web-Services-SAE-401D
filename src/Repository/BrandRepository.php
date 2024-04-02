<?php
// src/Repository/BrandRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Brands;

class BrandsRepository extends EntityRepository
{
    public function insertNewBrand($brandName)
    {
        $response = array();

        try {
            // if (empty($brandName)) {
            //     $response = array("status" => 0, "message" => "Brand's name required");
            //     return $response;
            // }
            // $existingBrand = $this->_em->getRepository(Brands::class)->findOneBy(['brandName' => $brandName]);
            // if ($existingBrand) {
            //     $response = array("status" => 0, "message" => "Brand already in the database");
            //     return $response;
            // }
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
}
