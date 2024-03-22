<?php
//src/Entity/Categories.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Categories")
 */

class Categories
{

    //déclaration des attributs & annotation doctrines

    /** @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    private int $category_id;

    /** @var string 
     * @ORM\Column(type="string")
     */

    private string $category_name;

    public function __toString()
    {
        return "Catégorie :{$this->category_id}, {$this->category_name}";
    }

    //getters & setters

    /**
     * get category_id
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * get category_name
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * set category_name
     * @param string $category_name
     * @return Categories
     */
    public function setCategoryName(string $category_name): Categories
    {
        $this->category_name = $category_name;
        return $this;
    }
}
