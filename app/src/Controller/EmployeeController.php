<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\Employees;
use App\Entity\Stores;
use App\Repository\EmployeeRepository;

/**
 * Class EmployeeController
 * @package App\Controller
 */
class EmployeeController
{
    /** @var EmployeeRepository */
    private $employeeRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var string */
    const API_KEY = "e8f1997c763";

    /**
     * EmployeeController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->employeeRepository = $entityManager->getRepository(Employees::class);
    }

    /**
     * Get all employees
     *
     * @OA\Get(
     *     path="/bikestores/employees",
     *     tags={"Employees"},
     *     summary="Get all employees",
     *     operationId="getAllEmployees",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employees not found"
     *     )
     * )
     */

    public function getAllEmployees()
    {
        $employees = $this->employeeRepository->findAll();

        echo json_encode($employees);
    }



    /**
     * Add a new employee
     *
     * @OA\Post(
     *     path="/bikestores/employees/create",
     *     tags={"Employees"},
     *     summary="Add a new employee",
     *     operationId="addEmployee",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"employee_name", "employee_email", "employee_password", "employee_role", "store_id"},
     *             @OA\Property(property="employee_name", type="string"),
     *             @OA\Property(property="employee_email", type="string"),
     *             @OA\Property(property="employee_password", type="string"),
     *             @OA\Property(property="employee_role", type="string"),
     *             @OA\Property(property="store_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee added successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
     */
    public function addEmployee()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_name']) && isset($_POST['employee_email']) && isset($_POST['employee_password']) && isset($_POST['employee_role']) && isset($_POST['store_id'])) {

            if (!isset($_POST["API_KEY"]) || $_POST['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $storeId = $_POST['store_id'];
            $employeeName = $_POST['employee_name'];
            $employeeEmail = $_POST['employee_email'];
            $employeePassword = $_POST['employee_password'];
            $employeeRole = $_POST['employee_role'];

            // Récupérer l'instance de Stores correspondant à storeId
            $store = $this->entityManager->getRepository(Stores::class)->find($storeId);
            if (!$store) {
                echo json_encode(["error" => "Store not found"]);
                return;
            }

            $employee = new Employees();
            $employee->setStore($store); // Passer l'instance de Stores à setStore()
            $employee->setEmployeeName($employeeName);
            $employee->setEmployeeEmail($employeeEmail);
            $employee->setEmployeePassword($employeePassword);
            $employee->setEmployeeRole($employeeRole);

            $this->entityManager->persist($employee);
            $this->entityManager->flush();

            echo json_encode(['success' => 'Employee added successfully']);
        }
    }


    /**
     * Update an existing employee
     *
     * @OA\Put(
     *     path="/bikestores/employees/update/{employeeId}",
     *     tags={"Employees"},
     *     summary="Update an existing employee",
     *     operationId="updateEmployee",
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         required=true,
     *         description="ID of the employee to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="employee_name", type="string"),
     *             @OA\Property(property="store_id", type="integer"),
     *             @OA\Property(property="employee_email", type="string"),
     *             @OA\Property(property="employee_password", type="string"),
     *             @OA\Property(property="employee_role", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee updated",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key or missing required fields"
     *     )
     * )
     */
    public function updateEmployee($params)
    {
        $employeeId = $params['employeeId'];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);

            if (!isset($_PUT["API_KEY"]) || $_PUT['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $employee = $this->entityManager->getRepository(Employees::class)->find($employeeId);

            if (isset($_PUT['employee_name'])) {
                $employeeName = $_PUT['employee_name'];
                $employee->setEmployeeName($employeeName);
            }

            if (isset($_PUT['store_id'])) {
                $storeId = $_PUT['store_id'];
                $store = $this->entityManager->getRepository(Stores::class)->find($storeId);
                if (!$store) {
                    echo json_encode(["error" => "Store not found"]);
                    return;
                }
                $employee->setStore($store);
            }

            if (isset($_PUT['employee_email'])) {
                $employeeEmail = $_PUT['employee_email'];
                $employee->setEmployeeEmail($employeeEmail);
            }

            if (isset($_PUT['employee_password'])) {
                $employeePassword = $_PUT['employee_password'];
                $employee->setEmployeePassword($employeePassword);
            }

            if (isset($_PUT['employee_role'])) {
                $employeeRole = $_PUT['employee_role'];
                $employee->setEmployeeRole($employeeRole);
            }

            $this->entityManager->flush();

            echo json_encode(['success' => 'Employee updated']);
            return $employee;
        } else {
            echo json_encode(["error" => "invalid request"]);
        }
    }


    /**
     * Delete an employee
     *
     * @OA\Delete(
     *     path="/bikestores/employees/delete/{employeeId}",
     *     tags={"Employees"},
     *     summary="Delete an existing employee",
     *     operationId="deleteEmployee",
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         required=true,
     *         description="ID of the employee to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee deleted",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid API Key"
     *     )
     * )
     */
    public function deleteEmployee($params)
    {
        $employeeId = $params['employeeId'];
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            parse_str(file_get_contents('php://input'), $_DELETE);

            if (!isset($_DELETE["API_KEY"]) || $_DELETE['API_KEY'] !== self::API_KEY) {
                echo json_encode(["error" => "Invalid API Key"]);
                return;
            }

            $employee = $this->entityManager->getRepository(Employees::class)->find($employeeId);

            if (!$employee) {
                echo json_encode(["error" => "Employee not found"]);
                return;
            }

            $this->entityManager->remove($employee);
            $this->entityManager->flush();

            echo json_encode(['success' => 'Employee deleted']);
        } else {
            echo json_encode(["error" => "invalid request"]);
            return;
        }
    }
}
