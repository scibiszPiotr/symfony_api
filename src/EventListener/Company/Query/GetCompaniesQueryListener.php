<?php

namespace App\EventListener\Company\Query;

use App\Event\Complany\Query\GetCompaniesQueryEvent;
use App\Repository\CompanyRepositoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: GetCompaniesQueryEvent::NAME, method: '__invoke')]
readonly class GetCompaniesQueryListener
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {
    }

    public function __invoke(GetCompaniesQueryEvent $event): void
    {
        $companies = $this->companyRepository->findAllCompanies();
        $event->setCompanies($companies);
    }
}
