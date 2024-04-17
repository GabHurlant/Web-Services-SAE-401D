<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Products;
use App\Entity\Brands;
use App\Entity\Categories;

/**
 * Class ProductRepository
 * 
 * Custom repository class for interacting with the Products entity.
 */
class ProductRepository extends EntityRepository
{
}
