<?php

namespace App\EventListener\Employee;

use App\Event\Employee\EmployeeCreatedEvent;
use App\Factory\EmployeeFactory;
use App\Repository\CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsEventListener(event: EmployeeCreatedEvent::NAME)]
readonly class CreateEmployeeListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private CompanyRepositoryInterface $companyRepository,
        private EmployeeFactory $employeeFactory,
    ) {
    }

    public function __invoke(EmployeeCreatedEvent $event): void
    {
        $employeeRequest = $event->getEmployeeRequest();
        $employee = $this->employeeFactory->createFromRequest($employeeRequest);

        $company = $this->companyRepository->findById($employeeRequest->companyId);
        if (!$company) {
            throw new \Exception(sprintf('Company with id %d not found.', $employeeRequest->companyId));
        }
        $employee->setCompany($company);

        $errors = $this->validator->validate($employee);
        if (count($errors) > 0) {
            throw new \Exception('Validation errors: ' . (string) $errors);
        }

        $this->em->persist($employee);
        $this->em->flush();
    }
}
