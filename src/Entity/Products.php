<?php
//src/Entity/Products.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Products")
 */

class Products
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

    /** @var int 
     * @ORM\Column(type="integer")
     */
    private int $brand_id;

    /** @var int 
     * @ORM\Column(type="integer")
     */

    private int $category_id;

    /** @var int
     * @ORM\Column(type="integer")
     */

    private int $model_year;

    /** @var string
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private string $list_price;

    public function __toString()
    {
        return "Produit :{$this->product_id}, {$this->product_name}, {$this->brand_id}, {$this->category_id}, {$this->model_year}, {$this->list_price}";
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
    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    /**
     * set brand_id
     * @param int $brand_id
     * @return Products
     */
    public function setBrandId(int $brand_id): Products
    {
        $this->brand_id = $brand_id;
        return $this;
    }

    /**
     * get category_id
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * set category_id
     * @param int $category_id
     * @return Products
     */
    public function setCategoryId(int $category_id): Products
    {
        $this->category_id = $category_id;
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
}
