<?php

/**
 * Namespace declaration
 */

namespace App\Entity;

/**
 * Importing necessary Doctrine classes and interfaces
 */

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @ORM\Table(name="Employees")
 *
 * Class Employees
 *
 * Represents an employee in the application.
 */
class Employees implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * The unique identifier of the employee.
     */
    private int $employee_id;

    /**
     * @ORM\ManyToOne(targetEntity=Stores::class, inversedBy="employees")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     *
     * The store where the employee works.
     */
    private $store;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * The name of the employee.
     */
    private string $employee_name;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * The email of the employee.
     */
    private string $employee_email;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * The password of the employee.
     */
    private string $employee_password;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * The role of the employee.
     */
    private string $employee_role;

    /**
     * Returns a string representation of the employee.
     *
     * @return string
     */
    public function __toString()
    {
        return "Employé :{$this->employee_id}, {$this->store}, {$this->employee_name}, {$this->employee_email}, {$this->employee_password}, {$this->employee_role}";
    }

    /**
     * Returns the employee's ID.
     *
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employee_id;
    }

    /**
     * Returns the ID of the store where the employee works.
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store->getStoreId();
    }

    /**
     * Returns the employee's name.
     *
     * @return string
     */
    public function getEmployeeName(): string
    {
        return $this->employee_name;
    }

    /**
     * Returns the employee's email.
     *
     * @return string
     */
    public function getEmployeeEmail(): string
    {
        return $this->employee_email;
    }

    /**
     * Returns the employee's password.
     *
     * @return string
     */
    public function getEmployeePassword(): string
    {
        return $this->employee_password;
    }

    /**
     * Returns the employee's role.
     *
     * @return string
     */
    public function getEmployeeRole(): string
    {
        return $this->employee_role;
    }

    /**
     * Sets the employee's name.
     *
     * @param string $employee_name
     * @return Employees
     */
    public function setEmployeeName(string $employee_name): Employees
    {
        $this->employee_name = $employee_name;
        return $this;
    }

    /**
     * Sets the store where the employee works.
     *
     * @param Stores $store
     * @return Employees
     */
    public function setStore(Stores $store): self
    {
        $this->store = $store;
        return $this;
    }

    /**
     * Sets the employee's email.
     *
     * @param string $employee_email
     * @return Employees
     */
    public function setEmployeeEmail(string $employee_email): Employees
    {
        $this->employee_email = $employee_email;
        return $this;
    }

    /**
     * Sets the employee's password.
     *
     * @param string $employee_password
     * @return Employees
     */
    public function setEmployeePassword(string $employee_password): Employees
    {
        $this->employee_password = $employee_password;
        return $this;
    }

    /**
     * Sets the employee's role.
     *
     * @param string $employee_role
     * @return Employees
     */
    public function setEmployeeRole(string $employee_role): Employees
    {
        $this->employee_role = $employee_role;
        return $this;
    }

    /**
     * Returns a JSON representation of the employee.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'employee_id' => $this->employee_id,
            'store' => $this->store,
            'employee_name' => $this->employee_name,
            'employee_email' => $this->employee_email,
            'employee_password' => $this->employee_password,
            'employee_role' => $this->employee_role
        ];
    }
}
