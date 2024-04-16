<?php
//src/Entity/Stores.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreRepository")
 * @ORM\Table(name="Stores")
 */

class Stores implements JsonSerializable
{

    //déclaration des attributs & annotation doctrines

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $store_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $store_name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $phone;

    /**
     * @var string
     * @ORM\Column(type="string", length=25)
     */

    private string $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $street;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    private string $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */

    private string $state;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */

    private string $zip_code;

    /**
     * @ORM\OneToMany(targetEntity=Stocks::class, mappedBy="store")
     */
    private Collection $stocks;

    /**
     * @ORM\OneToMany(targetEntity=Employees::class, mappedBy="store")
     */
    private Collection $employees;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->employees = new ArrayCollection();
    }

    public function __toString()
    {
        return "Store :{$this->store_id}, {$this->store_name}, {$this->phone}, {$this->email}, {$this->street}, {$this->city}, {$this->state}, {$this->zip_code}";
    }

    // Getters and setters

    /**
     * get store_id
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * set store_id
     * @param int $store_id
     * @return Stores
     */
    public function setStoreId(int $store_id): Stores
    {
        $this->store_id = $store_id;
        return $this;
    }

    /**
     * get store_name
     * @return string
     */
    public function getStoreName(): string
    {
        return $this->store_name;
    }

    /**
     * set store_name
     * @param string $store_name
     * @return Stores
     */
    public function setStoreName(string $store_name): Stores
    {
        $this->store_name = $store_name;
        return $this;
    }

    /**
     * get phone
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * set phone
     * @param string $phone
     * @return Stores
     */
    public function setPhone(string $phone): Stores
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * get email
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * set email
     * @param string $email
     * @return Stores
     */
    public function setEmail(string $email): Stores
    {
        $this->email = $email;
        return $this;
    }

    /**
     * get street
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * set street
     * @param string $street
     * @return Stores
     */
    public function setStreet(string $street): Stores
    {
        $this->street = $street;
        return $this;
    }

    /**
     * get city
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * set city
     * @param string $city
     * @return Stores
     */
    public function setCity(string $city): Stores
    {
        $this->city = $city;
        return $this;
    }

    /**
     * get state
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * set state
     * @param string $state
     * @return Stores
     */
    public function setState(string $state): Stores
    {
        $this->state = $state;
        return $this;
    }

    /**
     * get zip_code
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zip_code;
    }

    /**
     * set zip_code
     * @param string $zip_code
     * @return Stores
     */
    public function setZipCode(string $zip_code): Stores
    {
        $this->zip_code = $zip_code;
        return $this;
    }

    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function setStock(Collection $stocks): self
    {
        $this->stocks = $stocks;
        return $this;
    }

    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function setEmployees(Collection $employees): self
    {
        $this->employees = $employees;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'store_id' => $this->store_id,
            'store_name' => $this->store_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'stocks' => $this->stocks,
            'employees' => $this->employees
        ];
    }
}
