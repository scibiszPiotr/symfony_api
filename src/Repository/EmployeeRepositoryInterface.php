<?php

namespace App\Repository;

use App\Dto\EmployeeCollection;
use App\Dto\EmployeeDto;
use App\Entity\Employee;
use Doctrine\DBAL\LockMode;

interface EmployeeRepositoryInterface
{
    public function findById(int $id): ?Employee;

    public function findEmployeeById(int $id): ?EmployeeDto;

    public function findAllEmployees(): EmployeeCollection;
}