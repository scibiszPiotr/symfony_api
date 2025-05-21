<?php

namespace App\EventListener\Employee;

use App\Event\Employee\EmployeeDeletedEvent;
use App\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(event: EmployeeDeletedEvent::NAME)]
readonly class DeleteEmployeeListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private EmployeeRepositoryInterface $employeeRepository
    ) {
    }

    public function __invoke(EmployeeDeletedEvent $event): void
    {
        $employee = $this->employeeRepository->findById($event->getEmployeeId());
        if (!$employee) {
            throw new NotFoundHttpException('Employee not found');
        }
        $this->em->remove($employee);
        $this->em->flush();
    }
}