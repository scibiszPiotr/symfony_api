<?php

namespace App\Event\Complany;

use Symfony\Contracts\EventDispatcher\Event;

class DeleteCompanyEvent extends Event
{
    public const NAME = 'company.delete';

    public function __construct(private int $companyId)
    {
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }
}