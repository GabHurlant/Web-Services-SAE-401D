<?php
// src/Repository/EmployeeRepository.class.php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Employees;
use App\Entity\Stores;

class EmployeeRepository extends EntityRepository
{
}
