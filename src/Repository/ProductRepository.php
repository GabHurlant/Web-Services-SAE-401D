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

            $response = array("status" => 1, "status_message" => "Le produit $ProductName a été créée avec succès.");
        } catch (\Exception $e) {
            $response = array("status" => 0, "status_message" => "Erreur lors de la création du produit : " . $e->getMessage());
        }
        return json_encode($response);
    }
}
