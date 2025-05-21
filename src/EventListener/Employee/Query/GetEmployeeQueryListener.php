<?php

namespace App\EventListener\Employee\Query;

use App\Event\Employee\Query\GetEmployeeQueryEvent;
use App\Repository\EmployeeRepositoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: GetEmployeeQueryEvent::NAME)]
readonly class GetEmployeeQueryListener
{
    public function __construct(private EmployeeRepositoryInterface $employeeRepository) {
    }

    public function __invoke(GetEmployeeQueryEvent $event): void
    {
        $employee = $this->employeeRepository->findEmployeeById($event->getEmployeeId());
        $event->setEmployee($employee);
    }
}