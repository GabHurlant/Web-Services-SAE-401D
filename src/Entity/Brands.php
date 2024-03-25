<?php
//src/Entity/Brands.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Brands")
 */

class Brands
{

    // décclaration des attributs & annotation doctrines

    /** @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $brand_id;

    /** @var string 
     * @ORM\Column(type="string")
     */

    private string $brand_name;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="brands")
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString()
    {
        return "Marque :{$this->brand_id}, {$this->brand_name}";
    }

    //getters & setters

    /**
     * get brand_id
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    /**
     * get brand_name
     * @return string
     */
    public function getBrandName(): string
    {
        return $this->brand_name;
    }

    /**
     * set brand_name
     * @param string $brand_name
     * @return Brands
     */

    public function setBrandName(string $brand_name): Brands
    {
        $this->brand_name = $brand_name;
        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;
        return $this;
    }

    public function toArray(): array
    {
        return [
            "brand_id" => $this->getBrandId(),
            "brand_name" => $this->getBrandName()
        ];
    }
}
