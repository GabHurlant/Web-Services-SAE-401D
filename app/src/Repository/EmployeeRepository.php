<?php
// src/Repository/EmployeeRepository.class.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Employees;
use Entity\Stores;

class EmployeeRepository extends EntityRepository
{

    //méthodes get

    public function getAllEmployees()
    {
        $response = array();
        try {
            $employees = $this->_em->getRepository(Employees::class)->findAll();
            $response = array("status" => 1, "message" => "Employees found", "data" => $employees);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    //méthodes post

    public function addEmployee($storeId, $employeeName, $employeeEmail, $employeePassword, $employeeRole)
    {
        $response = array();
        try {
            $store = $this->_em->getRepository(Stores::class)->find($storeId);

            // Vérifiez si l'employé existe déjà
            $existingEmployee = $this->_em->getRepository(Employees::class)->findOneBy(['employee_name' => $employeeEmail]);
            if ($existingEmployee) {
                $response = array("status" => 0, "message" => "Employee already in the database");
                return $response;
            }

            $employee = new Employees();
            $employee->setStore($store);
            $employee->setEmployeeName($employeeName);
            $employee->setEmployeeEmail($employeeEmail);
            $employee->setEmployeePassword($employeePassword);
            $employee->setEmployeeRole($employeeRole);

            $this->_em->persist($employee);
            $this->_em->flush();

            $response = array("status" => 1, "message" => "Employee added", "data" => $employee);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    //méthodes put

    public function updateEmployee($employeeName, $employeeEmail, $employeePassword, $employeeRole)
    {
        $response = array();
        try {
            $employee = $this->_em->getRepository(Employees::class)->findOneBy(['employeeName' => $employeeName]);
            if (!$employee) {
                $response = array("status" => 0, "message" => "Employee not found");
                return $response;
            }
            $employee->setEmployeeName($employeeName);
            $employee->setEmployeeEmail($employeeEmail);
            $employee->setEmployeePassword($employeePassword);
            $employee->setEmployeeRole($employeeRole);

            $this->_em->flush();

            $response = array("status" => 1, "message" => "Employee updated", "data" => $employee);
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }

    //méthodes delete

    public function deleteEmployee($employeeName)
    {
        $response = array();
        try {
            $employee = $this->_em->getRepository(Employees::class)->findOneBy(['employeeName' => $employeeName]);
            if (!$employee) {
                $response = array("status" => 0, "message" => "Employee not found");
                return $response;
            }
            $this->_em->remove($employee);
            $this->_em->flush();

            $response = array("status" => 1, "message" => "Employee deleted");
            return $response;
        } catch (\Exception $e) {
            $response = array("status" => 0, "message" => "An error occurred: " . $e->getMessage());
            return $response;
        }
    }
}
