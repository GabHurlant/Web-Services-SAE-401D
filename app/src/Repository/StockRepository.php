<?php
// src/Repository/StockRepository.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Stocks;
use App\Entity\Stores;
use App\Entity\Products;

class StockRepository extends EntityRepository
{
}
