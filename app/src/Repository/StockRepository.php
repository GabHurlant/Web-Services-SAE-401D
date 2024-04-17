<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Stocks;
use App\Entity\Stores;
use App\Entity\Products;

/**
 * Class StockRepository
 * 
 * Custom repository class for interacting with the Stocks entity.
 */
class StockRepository extends EntityRepository
{
}
