<?php

namespace App\Event\Complany\Query;

use App\Dto\CompanyDto;
use Symfony\Contracts\EventDispatcher\Event;

class GetCompanyQueryEvent extends Event
{
    public const NAME = 'company.query.get_one';

    private ?CompanyDto $company = null;

    public function __construct(private readonly int $companyId) {
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getCompany(): ?CompanyDto
    {
        return $this->company;
    }

    public function setCompany(?CompanyDto $company): void
    {
        $this->company = $company;
    }
}