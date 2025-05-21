<?php

namespace App\Event\Complany;

use App\Request\CompanyRequest;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateCompanyEvent extends Event
{
    public const NAME = 'company.update';

    public function __construct(private int $companyId, private CompanyRequest $companyRequest) {
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getCompanyRequest(): CompanyRequest
    {
        return $this->companyRequest;
    }
}