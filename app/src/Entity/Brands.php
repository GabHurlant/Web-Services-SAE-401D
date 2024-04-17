<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @ORM\Table(name="Brands")
 */
class Brands implements JsonSerializable
{

    // Declaration of attributes & Doctrine annotations

    /** 
     * @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @OA\Property(description="The unique identifier of the brand.")
     */
    private int $brand_id;

    /** 
     * @var string 
     * @ORM\Column(type="string")
     * @OA\Property(description="The name of the brand.")
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

    // Getters and setters

    /**
     * Get brand_id
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    /**
     * Get brand_name
     * @return string
     */
    public function getBrandName(): string
    {
        return $this->brand_name;
    }

    /**
     * Set brand_name
     * @param string $brand_name
     * @return Brands
     */
    public function setBrandName(string $brand_name): Brands
    {
        $this->brand_name = $brand_name;
        return $this;
    }

    /**
     * Get the collection of products associated with this brand.
     *
     * @return Collection
     * @OA\Property(
     *     description="The collection of products associated with this brand.",
     *     @OA\Items(ref="#/components/schemas/Product")
     * )
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
        ];
    }
}
