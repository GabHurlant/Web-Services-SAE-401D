<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Categories;
use Repository\CategoryRepository;

/**
 * Class CategoriesController
 * @package App\Controller
 */
class CategoriesController
{
    /** @var EntityManager */
    private $entityManager;

    /** @var \App\Repository\CategoryRepository */
    private $categoryRepository;

    const API_KEY = "e8f1997c763";

    /**
     * CategoriesController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $entityManager->getRepository(Categories::class);
    }

    /**
     * Get a category by ID
     * @param array $categoryId
     */
    public function getCategory($categoryId)
    {
        $categoryId = $categoryId['categoryId'];
        $category = $this->categoryRepository->find($categoryId);
        header('Content-Type: application/json');
        echo json_encode($category);
    }

    /**
     * Get all categories
     */
    public function getAllCategories()
    {
        $categories = $this->categoryRepository->findAll();
        header('Content-Type: application/json');
        echo json_encode($categories);
    }

    /**
     * Create a new category
     * @param string $categoryName
     */
    public function createCategory($categoryName)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {

            // Verification of the API key
            if (!isset($_POST["API_KEY"]) || $_POST['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $categoryName = $_POST['categoryName'];

            $category = new Categories();
            $category->setCategoryName($categoryName);

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            echo json_encode(["success" => "Category created"]);
        } else {
            echo json_encode(["error" => "Category not created"]);
        }
    }

    /**
     * Update a category
     * @param array $params
     * @return mixed
     */
    public function updateCategory($params)
    {
        $categoryId = $params['categoryId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            if (isset($_PUT['categoryName'])) {
                $categoryName = $_PUT['categoryName'];

                $category = $this->entityManager->getRepository(Categories::class)->find($categoryId);

                $category->setCategoryName($categoryName);

                $this->entityManager->flush();

                echo json_encode(['success' => 'Category updated']);
                return $category;
            } else {
                echo json_encode(["error" => "Category name required"]);
            }
        } else {
            echo json_encode(["error" => "invalid request"]);
        }
    }

    /**
     * Delete a category
     * @param array $params
     */
    public function deleteCategory($params)
    {
        $categoryId = $params['categoryId'];
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            parse_str(file_get_contents('php://input'), $_DELETE);

            if (!isset($_DELETE["API_KEY"]) || $_DELETE['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $category = $this->entityManager->getRepository(Categories::class)->find($categoryId);

            if (!$category) {
                echo json_encode(["error" => "Category not found"]);
                return;
            }

            $this->entityManager->remove($category);
            $this->entityManager->flush();

            echo json_encode(['success' => 'Category deleted']);
        } else {
            echo json_encode(["error" => "invalid request"]);
            return;
        }
    }
}
