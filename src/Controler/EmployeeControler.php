<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Entity\Employees;
use Repository\EmployeeRepository;

class EmployeeController
{
    private $employeeRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->employeeRepository = $entityManager->getRepository(Employees::class);
    }

    public function getAllEmployees()
    {
        $employees = $this->employeeRepository->getAllEmployees();
        if ($employees['status'] == 0) {
            throw new \Exception($employees['message']);
        }
        return $employees['data'];
    }

    public function addEmployee($storeId, $employeeName, $employeeEmail, $employeePassword, $employeeRole)
    {
        $employee = $this->employeeRepository->addEmployee($storeId, $employeeName, $employeeEmail, $employeePassword, $employeeRole);
        if ($employee['status'] == 0) {
            throw new \Exception($employee['message']);
        }
        return $employee['data'];
    }

    public function updateEmployee($employeeName, $employeeEmail, $employeePassword, $employeeRole)
    {
        $employee = $this->employeeRepository->updateEmployee($employeeName, $employeeEmail, $employeePassword, $employeeRole);
        if ($employee['status'] == 0) {
            throw new \Exception($employee['message']);
        }
        return $employee['data'];
    }

    public function deleteEmployee($employeeName)
    {
        $employee = $this->employeeRepository->deleteEmployee($employeeName);
        if ($employee['status'] == 0) {
            throw new \Exception($employee['message']);
        }
        return $employee['data'];
    }
}
