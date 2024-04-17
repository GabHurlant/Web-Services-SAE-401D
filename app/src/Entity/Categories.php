<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="Categories")
 */
class Categories implements JsonSerializable
{

    // Declaration of attributes & Doctrine annotations

    /** 
     * @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @OA\Property(description="The unique identifier of the category.")
     */
    private int $category_id;

    /** 
     * @var string 
     * @ORM\Column(type="string")
     * @OA\Property(description="The name of the category.")
     */
    private string $category_name;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="category")
     * @OA\Property(
     *     description="The collection of products associated with this category.",
     *     @OA\Items(ref="#/components/schemas/Product")
     * )
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString()
    {
        return "Catégorie :{$this->category_id}, {$this->category_name}";
    }

    // Getters and setters

    /**
     * Get category_id
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * Get category_name
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * Set category_name
     * @param string $category_name
     * @return Categories
     */
    public function setCategoryName(string $category_name): Categories
    {
        $this->category_name = $category_name;
        return $this;
    }

    /**
     * Get the collection of products associated with this category.
     *
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Set the collection of products associated with this category.
     *
     * @param Collection $products
     * @return self
     */
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
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'products' => $this->products
        ];
    }
}
