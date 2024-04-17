<?php
// src/Repository/ProductsRepository.class.php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Products;
use App\Entity\Brands;
use App\Entity\Categories;

class ProductRepository extends EntityRepository
{
}
