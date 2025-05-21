<?php

namespace App\EventListener\Company\Query;

use App\Event\Complany\Query\GetCompanyQueryEvent;
use App\Repository\CompanyRepositoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: GetCompanyQueryEvent::NAME, method: '__invoke')]
readonly class GetCompanyQueryListener
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {
    }

    public function __invoke(GetCompanyQueryEvent $event): void
    {
        $company = $this->companyRepository->findCompanyById($event->getCompanyId());
        $event->setCompany($company);
    }
}
