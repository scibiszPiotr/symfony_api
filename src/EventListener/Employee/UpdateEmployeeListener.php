<?php

namespace App\EventListener\Employee;

use App\Event\Employee\EmployeeUpdatedEvent;
use App\Factory\EmployeeFactory;
use App\Repository\CompanyRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsEventListener(event: EmployeeUpdatedEvent::NAME)]
readonly class UpdateEmployeeListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private EmployeeRepositoryInterface $employeeRepository,
        private ValidatorInterface $validator,
        private CompanyRepositoryInterface $companyRepository,
        private EmployeeFactory $employeeFactory,
    ) {
    }

    public function __invoke(EmployeeUpdatedEvent $event): void
    {
        $employee = $this->employeeRepository->findById($event->getEmployeeId());
        if (!$employee) {
            throw new NotFoundHttpException(sprintf('Employee with id %d not found.', $event->getEmployeeId()));
        }

        $employeeRequest = $event->getEmployeeRequest();
        $this->employeeFactory->updateFromRequest($employee, $employeeRequest);

        $company = $this->companyRepository->findById($employeeRequest->companyId);
        if (!$company) {
            throw new \Exception(sprintf('Company with id %d not found.', $employeeRequest->companyId));
        }
        $employee->setCompany($company);

        $errors = $this->validator->validate($employee);
        if (count($errors) > 0) {
            throw new \Exception('Validation errors: ' . (string) $errors);
        }

        $this->em->flush();
    }
}