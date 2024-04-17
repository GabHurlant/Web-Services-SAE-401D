<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Employees;
use App\Entity\Stores;

/**
 * Class EmployeeRepository
 * 
 * Custom repository class for interacting with the Employees entity.
 */
class EmployeeRepository extends EntityRepository
{
}
