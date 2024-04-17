<?php
//src/Entity/Products.php

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

    //déclaration des attributs & annotation doctrines

    /** @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $product_id;

    /** @var string 
     * @ORM\Column(type="string")
     */

    private string $product_name;

    /**
     * @ORM\ManyToOne(targetEntity=Brands::class, inversedBy="products")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id")
     */
    private $brands;


    /** 
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     */
    private Categories $category;

    /** @var int
     * @ORM\Column(type="integer")
     */

    private int $model_year;

    /** @var string
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private string $list_price;

    /**
     * @ORM\OneToMany(targetEntity=Stocks::class, mappedBy="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
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

    //getters & setters

    /**
     * get product_id
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * get product_name
     * @return string
     */
    public function getProductName(): string
    {
        return $this->product_name;
    }

    /**
     * set product_name
     * @param string $product_name
     * @return Products
     */
    public function setProductName(string $product_name): Products
    {
        $this->product_name = $product_name;
        return $this;
    }

    /**
     * get brand_id
     * @return int
     */
    /**
     * get brand
     * @return Brands
     */
    public function getBrand(): Brands
    {
        return $this->brands;
    }

    /**
     * set brand
     * @param Brands $brand
     * @return Products
     */
    public function setBrand(Brands $brand): self
    {
        $this->brands = $brand;
        return $this;
    }


    /**
     * get category
     * @return Categories
     */
    public function getCategory(): Categories
    {
        return $this->category;
    }

    /**
     * set category
     * @param Categories $category
     * @return Products
     */
    public function setCategory(Categories $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * get model_year
     * @return int
     */
    public function getModelYear(): int
    {
        return $this->model_year;
    }

    /**
     * set model_year
     * @param int $model_year
     * @return Products
     */
    public function setModelYear(int $model_year): Products
    {
        $this->model_year = $model_year;
        return $this;
    }


    /**  
     * get list_price
     * @return string
     */
    public function getListPrice(): string
    {
        return $this->list_price;
    }

    /**
     * set list_price
     * @param string $list_price
     * @return Products
     */
    public function setListPrice(string $list_price): Products
    {
        $this->list_price = $list_price;
        return $this;
    }

    /**
     * get stocks
     * @return Collection
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    /**
     * set stocks
     * @param Collection $stocks
     * @return Products
     */
    public function setStocks(Collection $stocks): Products
    {
        $this->stocks = $stocks;
        return $this;
    }

    public function jsonSerialize()
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
