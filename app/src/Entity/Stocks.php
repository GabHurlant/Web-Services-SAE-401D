<?php

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

    // Declaration of attributes & Doctrine annotations

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @OA\Property(description="The unique identifier of the stock.")
     */
    private int $stock_id;

    /**
     * @ORM\ManyToOne(targetEntity=Stores::class, inversedBy="stocks")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     * @OA\Property(description="The store associated with this stock.")
     */
    private $store;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="stocks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * @OA\Property(description="The product associated with this stock.")
     */
    private $product;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @OA\Property(description="The quantity of the product in stock.")
     */
    private int $quantity;

    public function __toString()
    {
        return "Stock :{$this->stock_id}, {$this->store}, {$this->product}, {$this->quantity}";
    }

    // Getters and setters

    /**
     * Get stock_id
     * @return int
     */
    public function getStockId(): int
    {
        return $this->stock_id;
    }

    /**
     * Get store
     * @return Stores
     */
    public function getStore(): Stores
    {
        return $this->store;
    }

    /**
     * Get product
     * @return Products
     */
    public function getProduct(): Products
    {
        return $this->product;
    }

    /**
     * Get quantity
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Set store
     * @param Stores $store
     * @return Stocks
     */
    public function setStore(Stores $store): self
    {
        $this->store = $store;
        return $this;
    }

    /**
     * Set product
     * @param Products $product
     * @return Stocks
     */
    public function setProduct(Products $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Set quantity
     * @param int $quantity
     * @return Stocks
     */
    public function setQuantity(int $quantity): Stocks
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'stock_id' => $this->stock_id,
            'store' => $this->store,
            'product' => $this->product,
            'quantity' => $this->quantity
        ];
    }
}
