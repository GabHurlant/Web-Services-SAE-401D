<?php
//src/Entity/Stocks.php

namespace App\Entity;

use App\Entity\Products;
use App\Entity\Stores;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 * @ORM\Table(name="Stocks")
 */

class Stocks implements JsonSerializable
{

    //déclaration des attributs & annotation doctrines

    /** @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $stock_id;

    /**
     * @ORM\ManyToOne(targetEntity=Stores::class, inversedBy="stocks")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private $store;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="stocks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     */
    private $product;

    /** @var int 
     * @ORM\Column(type="integer")
     */


    private int $quantity;



    public function __toString()
    {
        return "Stock :{$this->stock_id}, {$this->store}, {$this->product}, {$this->quantity}";
    }

    //getters & setters

    /**
     * get stock_id
     * @return int
     */
    public function getStockId(): int
    {
        return $this->stock_id;
    }

    /**
     * get store
     * @return Stores
     */
    public function getStore(): Stores
    {
        return $this->store;
    }

    /**
     * get product
     * @return Products
     * 
     */
    public function getProduct(): Products
    {
        return $this->product;
    }

    /**
     * get quantity
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * set store
     * @param Stores $store
     * @return Stocks
     */
    public function setStore(Stores $store): self
    {
        $this->store = $store;
        return $this;
    }

    /**
     * set product_id
     * @param int $product_id
     * @return Stocks
     */
    /**
     * set product
     * @param Products $product
     * @return Stocks
     */
    public function setProduct(Products $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * set quantity
     * @param int $quantity
     * @return Stocks
     */
    public function setQuantity(int $quantity): Stocks
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'stock_id' => $this->stock_id,
            'store' => $this->store,
            'product' => $this->product,
            'quantity' => $this->quantity
        ];
    }
}
