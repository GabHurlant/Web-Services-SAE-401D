<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Categories;
use Repository\CategoryRepository;

class CategoriesController
{
    private $categoryRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->categoryRepository = $entityManager->getRepository(Categories::class);
    }

    public function getCategory($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);
        if ($category['status'] == 0) {
            throw new \Exception($category['message']);
        }
        return $category['data'];
    }

    public function getAllCategories()
    {
        $categories = $this->categoryRepository->getAllCategories();
        if ($categories['status'] == 0) {
            throw new \Exception($categories['message']);
        }
        return $categories['data'];
    }

    public function findProductsByCategoryName($categoryName)
    {
        $products = $this->categoryRepository->findProductsByCategoryName($categoryName);
        if ($products['status'] == 0) {
            throw new \Exception($products['message']);
        }
        return $products['data'];
    }

    public function createCategory($categoryName)
    {
        $category = $this->categoryRepository->insertNewCategory($categoryName);
        if ($category['status'] == 0) {
            throw new \Exception($category['message']);
        }
        return $category['data'];
    }

    public function updateCategory($categoryName, $newCategoryName)
    {
        $category = $this->categoryRepository->updateCategory($categoryName, $newCategoryName);
        if ($category['status'] == 0) {
            throw new \Exception($category['message']);
        }
        return $category['data'];
    }

    public function deleteCategory($categoryName)
    {
        $category = $this->categoryRepository->deleteCategory($categoryName);
        if ($category['status'] == 0) {
            throw new \Exception($category['message']);
        }
        return $category['data'];
    }
}
