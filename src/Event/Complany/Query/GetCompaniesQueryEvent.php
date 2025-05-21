<?php

namespace App\Event\Complany\Query;

use App\Dto\CompanyCollection;
use Symfony\Contracts\EventDispatcher\Event;

class GetCompaniesQueryEvent extends Event
{
    public const NAME = 'companies.query.get_all';

    private CompanyCollection $companies;

    public function getCompanies(): CompanyCollection
    {
        return $this->companies;
    }

    public function setCompanies(CompanyCollection $companies): void
    {
        $this->companies = $companies;
    }
}