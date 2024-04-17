<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Categories;
use App\Repository\CategoryRepository;

/**
 * Class CategoriesController
 * @package App\Controller
 */
class CategoriesController
{
    /** @var EntityManager */
    private $entityManager;

    /** @var CategoryRepository */
    private $categoryRepository;

    /** @var string */
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
     * Get a specific category
     * @param mixed $categoryId
     *
     * @OA\Get(
     *     path="/bikestores/categories/{categoryId}",
     *     summary="Get a specific category",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Categories")
     *     )
     * )
     */
    public function getCategory($categoryId)
    {

        $categoryId = $categoryId['categoryId'];
        $category = $this->categoryRepository->find($categoryId);

        echo json_encode($category);
    }

    /**
     * Get all categories
     *
     * @OA\Get(
     *     path="/bikestores/categories",
     *     summary="Get all categories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Categories")
     *     )
     * )
     */
    public function getAllCategories()
    {
        $categories = $this->categoryRepository->findAll();

        echo json_encode($categories);
    }

    /**
     * Create a new category
     * @param string $categoryName
     *
     * @OA\Post(
     *     path="/bikestores/categories/create",
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category object to be created",
     *         @OA\JsonContent(ref="#/components/schemas/Categories")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category created successfully"
     *     )
     * )
     */
    public function createCategory($categoryName)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {

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
     * Update an existing category
     * @param array $params
     * @return Categories|null
     *
     * @OA\Put(
     *     path="/bikestores/categories/update/{categoryId}",
     *     summary="Update an existing category",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category object to be updated",
     *         @OA\JsonContent(ref="#/components/schemas/Categories")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully"
     *     )
     * )
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
     *
     * @OA\Delete(
     *     path="/bikestores/categories/delete/{categoryId}",
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     )
     * )
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
