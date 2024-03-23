<?php
//src/Entity/Stocks.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Stocks")
 */

class Stocks
{

    //déclaration des attributs & annotation doctrines

    /** @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $stock_id;

    /** @var int 
     * @ORM\Column(type="integer")
     */

    private int $store_id;

    /** @var int 
     * @ORM\Column(type="integer")
     */

    private int $product_id;

    /** @var int 
     * @ORM\Column(type="integer")
     */

    private int $quantity;

    public function __toString()
    {
        return "Stock :{$this->stock_id}, {$this->store_id}, {$this->product_id}, {$this->quantity}";
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
     * get store_id
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * get product_id
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
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
     * set store_id
     * @param int $store_id
     * @return Stocks
     */
    public function setStoreId(int $store_id): Stocks
    {
        $this->store_id = $store_id;
        return $this;
    }

    /**
     * set product_id
     * @param int $product_id
     * @return Stocks
     */
    public function setProductId(int $product_id): Stocks
    {
        $this->product_id = $product_id;
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
}
