<?php
// src/Repository/ProductsRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Products;
use Entity\Brands;
use Entity\Categories;

class ProductRepository extends EntityRepository
{

    //methodes get
    public function getAllProducts()
    {
        try {
            $products = $this->_em->getRepository(Products::class)->findAll();
            return $products;
        } catch (\Exception $e) {
            return array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
    }

    public function getProductById($id)
    {
        try {
            $product = $this->_em->getRepository(Products::class)->findOneBy(['product_id' => $id]);
            return $product;
        } catch (\Exception $e) {
            return array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
    }

    //methodes post
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

    //methodes put

    public function updateProduct($productId, $productName, $brandId, $categoryId, $price, $year)
    {
        try {
            $entityManager = $this->getEntityManager();

            $brand = $entityManager->getRepository(Brands::class)->find($brandId);
            if (!$brand) {
                return array("status" => 0, "message" => "Brand not found");
            }

            $category = $entityManager->getRepository(Categories::class)->find($categoryId);
            if (!$category) {
                return array("status" => 0, "message" => "Category not found");
            }

            $product = $entityManager->getRepository(Products::class)->find($productId);
            if (!$product) {
                return array("status" => 0, "message" => "Product not found");
            }

            $product->setProductName($productName);
            $product->setBrand($brand);
            $product->setCategory($category);
            $product->setListPrice($price);
            $product->setModelYear($year);
            $entityManager->flush();

            return $product;
        } catch (\Exception $e) {
            return array("status" => 0, "message" => $e->getMessage());
        }
    }

    //methodes delete

    public function deleteProduct($id)
    {
        try {
            $product = $this->_em->getRepository(Products::class)->find($id);
            if (!$product) {
                return array("status" => 0, "message" => "Product not found");
            }

            $this->_em->remove($product);
            $this->_em->flush();

            return array("status" => 1, "message" => "Product deleted successfully");
        } catch (\Exception $e) {
            return array("status" => 0, "message" => $e->getMessage());
        }
    }
}
