<?php

/**
 * Namespace declaration
 */

namespace App\Entity;

/**
 * Importing necessary Doctrine classes and interfaces
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @ORM\Table(name="Brands")
 *
 * Class Brands
 *
 * Represents a brand in the application.
 */
class Brands implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * The unique identifier of the brand.
     */
    private int $brand_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * The name of the brand.
     */
    private string $brand_name;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="brands")
     *
     * The products associated with the brand.
     */
    private Collection $products;

    /**
     * Brands constructor.
     *
     * Initializes the products collection.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Returns a string representation of the brand.
     *
     * @return string
     */
    public function __toString()
    {
        return "Marque :{$this->brand_id}, {$this->brand_name}";
    }

    /**
     * Returns the brand's ID.
     *
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    /**
     * Returns the brand's name.
     *
     * @return string
     */
    public function getBrandName(): string
    {
        return $this->brand_name;
    }

    /**
     * Sets the brand's name.
     *
     * @param string $brand_name
     * @return Brands
     */
    public function setBrandName(string $brand_name): Brands
    {
        $this->brand_name = $brand_name;
        return $this;
    }

    /**
     * Returns the products associated with the brand.
     *
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Sets the products associated with the brand.
     *
     * @param Collection $products
     * @return Brands
     */
    public function setProducts(Collection $products): self
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Returns a JSON representation of the brand.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
        ];
    }
}
