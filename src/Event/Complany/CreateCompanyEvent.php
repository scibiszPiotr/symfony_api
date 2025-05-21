<?php

namespace App\Event\Complany;

use App\Request\CompanyRequest;
use Symfony\Contracts\EventDispatcher\Event;

class CreateCompanyEvent extends Event
{
    public const NAME = 'company.create';


    public function __construct(private CompanyRequest $companyRequest) {
    }

    public function getCompanyRequest(): CompanyRequest
    {
        return $this->companyRequest;
    }
}
