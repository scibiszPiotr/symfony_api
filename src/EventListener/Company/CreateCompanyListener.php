<?php

namespace App\EventListener\Company;

use App\Event\Complany\CreateCompanyEvent;
use App\Factory\CompanyFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[AsEventListener(event: CreateCompanyEvent::NAME, method: '__invoke')]
readonly class CreateCompanyListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private CompanyFactory $companyFactory,
    ) {
    }

    public function __invoke(CreateCompanyEvent $event): void
    {
        $companyRequest = $event->getCompanyRequest();
        $company = $this->companyFactory->createFromRequest($companyRequest);

        $errors = $this->validator->validate($company);
        if (count($errors) > 0) {
            throw new \Exception('Validation errors: ' . (string) $errors);
        }

        $this->em->persist($company);
        $this->em->flush();
    }
}
