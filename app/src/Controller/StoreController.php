<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Stores;
use Repository\StoreRepository;

class StoreController
{
    private $storeRepository;
    private $entityManager;

    const API_KEY = 'e8f1997c763';

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->storeRepository = $entityManager->getRepository(Stores::class);
    }

    public function getStores()
    {
        $stores = $this->storeRepository->findAll();
        header('Content-Type: application/json');
        echo json_encode($stores);
    }

    public function getEmployeesByStoreId($storeName)
    {
        $employees = $this->storeRepository->getEmployeesByStoreName($storeName);
        if ($employees['status'] == 0) {
            throw new \Exception($employees['message']);
        }
        return $employees['data'];
    }


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
