<?php
// src/Repository/ProductsRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Products;

class ProductsRepository extends EntityRepository
{

    public function insertNewProduct($ProductName, $ModelYear, $ListPrice)
    {
        $response = array();

        try {
            $Product = new Products();
            $Product->setProductName($ProductName);
            $Product->setModelyear($ModelYear);
            $Product->setListPrice($ListPrice);

            $this->_em->persist($Product);
            $this->_em->flush();

            $response = array("status" => 1, "status_message" => "$ProductName Added successfully.");
        } catch (\Exception $e) {
            $response = array("status" => 0, "status_message" => "Error while creating : " . $e->getMessage());
        }
        return json_encode($response);
    }
}
