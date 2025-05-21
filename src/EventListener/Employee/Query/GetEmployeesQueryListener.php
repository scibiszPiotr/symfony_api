<?php

namespace App\EventListener\Employee\Query;

use App\Event\Employee\Query\GetEmployeesQueryEvent;
use App\Repository\EmployeeRepositoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: GetEmployeesQueryEvent::NAME)]
readonly class GetEmployeesQueryListener
{
    public function __construct(private EmployeeRepositoryInterface $employeeRepository) {
    }

    public function __invoke(GetEmployeesQueryEvent $event): void
    {
        $employees = $this->employeeRepository->findAllEmployees();
        $event->setEmployees($employees);
    }
}