<?php

// src/Entity/Employees.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="Repository\EmployeeRepository")
 * @ORM\Table(name="Employees")
 */

class Employees implements JsonSerializable
{

    //déclaration des attributs & annotation doctrines

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $employee_id;

    /**
     * @ORM\ManyToOne(targetEntity=Stores::class, inversedBy="employees")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     */

    private $store;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $employee_name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $employee_email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $employee_password;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $employee_role;

    public function __toString()
    {
        return "Employé :{$this->employee_id}, {$this->store}, {$this->employee_name}, {$this->employee_email}, {$this->employee_password}, {$this->employee_role}";
    }

    //getters & setters

    /**
     * get employee_id
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employee_id;
    }

    /**
     * get store_id
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store->getStoreId();
    }

    /**
     * get employee_name
     * @return string
     */
    public function getEmployeeName(): string
    {
        return $this->employee_name;
    }

    /**
     * get employee_email
     * @return string
     */
    public function getEmployeeEmail(): string
    {
        return $this->employee_email;
    }

    /**
     * get employee_password
     * @return string
     */
    public function getEmployeePassword(): string
    {
        return $this->employee_password;
    }

    /**
     * get employee_role
     * @return string
     */
    public function getEmployeeRole(): string
    {
        return $this->employee_role;
    }


    /**
     * set employee_name
     * @param string $employee_name
     * @return Employees
     */
    public function setEmployeeName(string $employee_name): Employees
    {
        $this->employee_name = $employee_name;
        return $this;
    }

    /**
     * set store_id
     * @param Stores $store
     * @return Employees
     */
    public function setStore(Stores $store): self
    {
        $this->store = $store;
        return $this;
    }

    /**
     * set employee_email
     * @param string $employee_email
     * @return Employees
     */
    public function setEmployeeEmail(string $employee_email): Employees
    {
        $this->employee_email = $employee_email;
        return $this;
    }

    /**
     * set employee_password
     * @param string $employee_password
     * @return Employees
     */
    public function setEmployeePassword(string $employee_password): Employees
    {
        $this->employee_password = $employee_password;
        return $this;
    }

    /**
     * set employee_role
     * @param string $employee_role
     * @return Employees
     */
    public function setEmployeeRole(string $employee_role): Employees
    {
        $this->employee_role = $employee_role;
        return $this;
    }

    public function jsonSerialize(): mixed
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
