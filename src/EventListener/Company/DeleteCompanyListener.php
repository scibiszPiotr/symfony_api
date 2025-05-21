<?php

namespace App\EventListener\Company;

use App\Event\Complany\DeleteCompanyEvent;
use App\Repository\CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(event: DeleteCompanyEvent::NAME, method: '__invoke')]
readonly class DeleteCompanyListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function __invoke(DeleteCompanyEvent $event): void
    {
        $company = $this->companyRepository->findById($event->getCompanyId());

        if (!$company) {
            throw new NotFoundHttpException(sprintf('Company with id %d not found.', $event->getCompanyId()));
        }

        $this->em->remove($company);
        $this->em->flush();
    }
}
