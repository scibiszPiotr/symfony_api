<?php

namespace App\EventListener\Company;

use App\Event\Complany\UpdateCompanyEvent;
use App\Factory\CompanyFactory;
use App\Repository\CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsEventListener(event: UpdateCompanyEvent::NAME, method: '__invoke')]
readonly class UpdateCompanyListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private CompanyFactory $companyFactory,
        private CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function __invoke(UpdateCompanyEvent $event): void
    {
        $company = $this->companyRepository->findById($event->getCompanyId());

        if (!$company) {
            throw new NotFoundHttpException('Company with ID ' . $event->getCompanyId() . ' not found');
        }

        $companyRequest = $event->getCompanyRequest();

        $this->companyFactory->updateFromRequest($company, $companyRequest);

        $errors = $this->validator->validate($company);
        if (count($errors) > 0) {
            throw new \Exception('Validation errors: ' . (string) $errors);
        }

        $this->em->flush();
    }
}
