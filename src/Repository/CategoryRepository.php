<?php
// src/Repository/CategoryRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Categories;

class CategoryRepository extends EntityRepository
{

    //méthodes get
    public function getAllCategories()
    {
        $response = array();
        try {
            $categories = $this->_em->getRepository(Categories::class)->findAll();
            $response = array("status" => 1, "message" => "Categories found", "data" => $categories);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    public function findProductsByCategoryName($categoryName)
    {
        $response = array();
        try {
            $category = $this->findOneBy(['category_name' => $categoryName]);
            if ($category) {
                $products = $category->getProducts()->toArray();
                $response = array("status" => 1, "message" => "Products found", "data" => $products);
            } else {
                $response = array("status" => 0, "message" => "Category not found");
            }
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
        return $response;
    }

    //méthodes post

    public function insertNewCategory($categoryName)
    {
        $response = array();

        try {
            if (empty($categoryName)) {
                $response = array("status" => 0, "message" => "Category's name required");
                return $response;
            }
            $existingCategory = $this->_em->getRepository(Categories::class)->findOneBy(['category_name' => $categoryName]);
            if ($existingCategory) {
                $response = array("status" => 0, "message" => "Category already in the database");
                return $response;
            }
            $category = new Categories();
            $category->setCategoryName($categoryName);
            $this->_em->persist($category);
            $this->_em->flush();
            $response = array("status" => 1, "message" => "Category added", "data" => $category);
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
        return $response;
    }

    //méthodes put

    public function updateCategory($categoryName, $newCategoryName)
    {
        $response = array();
        try {
            $category = $this->findOneBy(['category_name' => $categoryName]);
            if ($category) {
                $category->setCategoryName($newCategoryName);
                $this->_em->persist($category);
                $this->_em->flush();
                $response = array("status" => 1, "message" => "Category updated", "data" => $category);
            } else {
                $response = array("status" => 0, "message" => "Category not found");
            }
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
        return $response;
    }

    //méthodes delete

    public function deleteCategory($categoryName)
    {
        $response = array();
        try {
            $category = $this->findOneBy(['category_name' => $categoryName]);
            if ($category) {
                $this->_em->remove($category);
                $this->_em->flush();
                $response = array("status" => 1, "message" => "Category deleted");
            } else {
                $response = array("status" => 0, "message" => "Category not found");
            }
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
        }
        return $response;
    }
}
