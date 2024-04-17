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
     */
    private int $category_id;

    /** 
     * @var string 
     * @ORM\Column(type="string")
     */
    private string $category_name;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="category")
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

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'products' => $this->products
        ];
    }
}
