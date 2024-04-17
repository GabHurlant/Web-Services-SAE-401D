<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Stores;
use App\Repository\StoreRepository;

/**
 * Class StoreController
 * @package App\Controller
 */
class StoreController
{
    /** @var StoreRepository */
    private $storeRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var string */
    const API_KEY = 'e8f1997c763';

    /**
     * StoreController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->storeRepository = $entityManager->getRepository(Stores::class);
    }

    /**
     * Get all stores
     *
     * @OA\Get(
     *     path="/stores",
     *     tags={"Stores"},
     *     summary="Get all stores",
     *     operationId="getStores",
     *     @OA\Response(
     *         response=200,
     *         description="An array of stores",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Store")
     *         )
     *     )
     * )
     */
    public function getStores()
    {
        $stores = $this->storeRepository->findAll();

        echo json_encode($stores);
    }

    // Uncomment to enable this method
    // public function getEmployeesByStoreId($storeName)
    // {
    //     $employees = $this->storeRepository->getEmployeesByStoreName($storeName);
    //     if ($employees['status'] == 0) {
    //         throw new \Exception($employees['message']);
    //     }
    //     return $employees['data'];
    // }

    /**
     * Create a new store
     *
     * @OA\Post(
     *     path="/stores/create",
     *     tags={"Stores"},
     *     summary="Create a new store",
     *     operationId="createStore",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"storeName", "storeZip", "storeCity", "storeStreet", "storeState", "phone", "email"},
     *             @OA\Property(property="storeName", type="string"),
     *             @OA\Property(property="storeZip", type="string"),
     *             @OA\Property(property="storeCity", type="string"),
     *             @OA\Property(property="storeStreet", type="string"),
     *             @OA\Property(property="storeState", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store created",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
     */
    public function createStore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['storeName']) && isset($_POST['storeZip']) && isset($_POST['storeCity']) && isset($_POST['storeStreet']) && isset($_POST['storeState']) && isset($_POST['phone']) && isset($_POST['email'])) {

            if (!isset($_POST["API_KEY"]) || $_POST['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $storeName = $_POST['storeName'];
            $storeZip = $_POST['storeZip'];
            $storeCity = $_POST['storeCity'];
            $storeStreet = $_POST['storeStreet'];
            $storeState = $_POST['storeState'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];

            $store = new Stores();
            $store->setStoreName($storeName);
            $store->setPhone($phone);
            $store->setEmail($email);
            $store->setZipCode($storeZip);
            $store->setCity($storeCity);
            $store->setStreet($storeStreet);
            $store->setState($storeState);

            $this->entityManager->persist($store);
            $this->entityManager->flush();

            echo json_encode(["success" => "Store created"]);
        } else {

            echo json_encode(["error" => "Store not created"]);
        }
    }

    /**
     * Update an existing store
     *
     * @OA\Put(
     *     path="/stores/update/{storeId}",
     *     tags={"Stores"},
     *     summary="Update an existing store",
     *     operationId="updateStore",
     *     @OA\Parameter(
     *         name="storeId",
     *         in="path",
     *         required=true,
     *         description="ID of the store to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"storeName", "storeZip", "storeCity", "storeStreet", "storeState", "phone", "email"},
     *             @OA\Property(property="storeName", type="string"),
     *             @OA\Property(property="storeZip", type="string"),
     *             @OA\Property(property="storeCity", type="string"),
     *             @OA\Property(property="storeStreet", type="string"),
     *             @OA\Property(property="storeState", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store updated",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
     */
    public function updateStore($params)
    {
        $storeId = $params['storeId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key or API Key missing"]);
                return;
            }

            $store = $this->entityManager->getRepository(Stores::class)->find($storeId);

            if (isset($_PUT['storeName'])) {
                $storeName = $_PUT['storeName'];
                $store->setStoreName($storeName);
            }

            if (isset($_PUT['storeZip'])) {
                $storeZip = $_PUT['storeZip'];
                $store->setZipCode($storeZip);
            }

            if (isset($_PUT['storeCity'])) {
                $storeCity = $_PUT['storeCity'];
                $store->setCity($storeCity);
            }

            if (isset($_PUT['storeStreet'])) {
                $storeStreet = $_PUT['storeStreet'];
                $store->setStreet($storeStreet);
            }

            if (isset($_PUT['storeState'])) {
                $storeState = $_PUT['storeState'];
                $store->setState($storeState);
            }

            if (isset($_PUT['phone'])) {
                $phone = $_PUT['phone'];
                $store->setPhone($phone);
            }

            if (isset($_PUT['email'])) {
                $email = $_PUT['email'];
                $store->setEmail($email);
            }

            $this->entityManager->flush();

            echo json_encode(['success' => 'Store updated']);
            return $store;
        } else {
            echo json_encode(["error" => "invalid request"]);
        }
    }
}
