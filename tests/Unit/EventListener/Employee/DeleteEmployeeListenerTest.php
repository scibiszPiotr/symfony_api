<?php

namespace App\Tests\Unit\EventListener\Employee;

use App\Entity\Employee;
use App\Event\Employee\EmployeeDeletedEvent;
use App\EventListener\Employee\DeleteEmployeeListener;
use App\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteEmployeeListenerTest extends TestCase
{
    public function testOnEmployeeDeletedSuccess(): void
    {
        $employeeId = 27;
        $employee = new Employee();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);

        $event = new EmployeeDeletedEvent($employeeId);

        $employeeRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeId))
            ->willReturn($employee);

        $entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($employee));

        $entityManagerMock->expects($this->once())
            ->method('flush');

        $listener = new DeleteEmployeeListener($entityManagerMock, $employeeRepositoryMock);

        $listener($event);

        $this->addToAssertionCount(1);
    }

    public function testOnEmployeeDeletedNotFound(): void
    {
        $employeeId = 31;

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);

        $event = new EmployeeDeletedEvent($employeeId);

        $employeeRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeId))
            ->willReturn(null);

        $entityManagerMock->expects($this->never())
            ->method('remove');

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new DeleteEmployeeListener($entityManagerMock, $employeeRepositoryMock);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Employee not found');

        $listener($event);
    }
}