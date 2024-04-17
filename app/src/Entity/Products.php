<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Brands;
use App\Entity\Categories;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="Products")
 */
class Products implements JsonSerializable
{

    // Declaration of attributes & Doctrine annotations

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @OA\Property(description="The unique identifier of the product.")
     */
    private int $product_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @OA\Property(description="The name of the product.")
     */
    private string $product_name;

    /**
     * @ORM\ManyToOne(targetEntity=Brands::class, inversedBy="products")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id")
     * @OA\Property(description="The brand associated with this product.")
     */
    private $brands;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     * @OA\Property(description="The category associated with this product.")
     */
    private Categories $category;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @OA\Property(description="The model year of the product.")
     */
    private int $model_year;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @OA\Property(description="The list price of the product.")
     */
    private string $list_price;

    /**
     * @ORM\OneToMany(targetEntity=Stocks::class, mappedBy="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * @OA\Property(description="The stocks associated with this product.")
     */
    private  Collection $stocks;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
    }

    public function __toString()
    {
        return "Produit :{$this->product_id}, {$this->product_name}, {$this->brands}, {$this->category}, {$this->model_year}, {$this->list_price}";
    }

    // Getters and setters

    /**
     * Get product_id
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * Get product_name
     * @return string
     */
    public function getProductName(): string
    {
        return $this->product_name;
    }

    /**
     * Set product_name
     * @param string $product_name
     * @return Products
     */
    public function setProductName(string $product_name): Products
    {
        $this->product_name = $product_name;
        return $this;
    }

    /**
     * Get brand
     * @return Brands
     */
    public function getBrand(): Brands
    {
        return $this->brands;
    }

    /**
     * Set brand
     * @param Brands $brand
     * @return Products
     */
    public function setBrand(Brands $brand): self
    {
        $this->brands = $brand;
        return $this;
    }

    /**
     * Get category
     * @return Categories
     */
    public function getCategory(): Categories
    {
        return $this->category;
    }

    /**
     * Set category
     * @param Categories $category
     * @return Products
     */
    public function setCategory(Categories $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get model_year
     * @return int
     */
    public function getModelYear(): int
    {
        return $this->model_year;
    }

    /**
     * Set model_year
     * @param int $model_year
     * @return Products
     */
    public function setModelYear(int $model_year): Products
    {
        $this->model_year = $model_year;
        return $this;
    }

    /**
     * Get list_price
     * @return string
     */
    public function getListPrice(): string
    {
        return $this->list_price;
    }

    /**
     * Set list_price
     * @param string $list_price
     * @return Products
     */
    public function setListPrice(string $list_price): Products
    {
        $this->list_price = $list_price;
        return $this;
    }

    /**
     * Get stocks
     * @return Collection
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    /**
     * Set stocks
     * @param Collection $stocks
     * @return Products
     */
    public function setStocks(Collection $stocks): Products
    {
        $this->stocks = $stocks;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @return array
     */
    public function jsonSerialize(): array
    {
        $stocks = $this->getStocks();
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'brand' => $this->brands->jsonSerialize(),
            'category' => $this->category->jsonSerialize(),
            'model_year' => $this->model_year,
            'list_price' => $this->list_price,
            'stocks' => $this->stocks
        ];
    }
}
