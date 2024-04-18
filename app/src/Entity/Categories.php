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
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="Categories")
 *
 * Class Categories
 *
 * Represents a category in the application.
 */
class Categories implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * The unique identifier of the category.
     */
    private int $category_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * The name of the category.
     */
    private string $category_name;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="category")
     *
     * The products associated with the category.
     */
    private Collection $products;

    /**
     * Categories constructor.
     *
     * Initializes the products collection.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Returns a string representation of the category.
     *
     * @return string
     */
    public function __toString()
    {
        return "Catégorie :{$this->category_id}, {$this->category_name}";
    }

    /**
     * Returns the category's ID.
     *
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * Returns the category's name.
     *
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * Sets the category's name.
     *
     * @param string $category_name
     * @return Categories
     */
    public function setCategoryName(string $category_name): Categories
    {
        $this->category_name = $category_name;
        return $this;
    }

    /**
     * Returns the products associated with the category.
     *
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Sets the products associated with the category.
     *
     * @param Collection $products
     * @return Categories
     */
    public function setProducts(Collection $products): self
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Returns a JSON representation of the category.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'products' => $this->products
        ];
    }
}
