<?php

namespace App\Repository;

use App\Dto\CompanyCollection;
use App\Dto\CompanyDto;
use App\Entity\Company;

interface CompanyRepositoryInterface
{
    public function findById(int $id): ?Company;

    public function findCompanyById(int $id): ?CompanyDto;

    public function findAllCompanies(): CompanyCollection;
}