<?php

namespace App\Tests\Unit\EventListener\Employee\Query;

use App\Dto\EmployeeCollection;
use App\Dto\EmployeeDto;
use App\Event\Employee\Query\GetEmployeesQueryEvent;
use App\EventListener\Employee\Query\GetEmployeesQueryListener;
use App\Repository\EmployeeRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetEmployeesQueryListenerTest extends TestCase
{
    public function testOnGetEmployeesQuery(): void
    {
        $employee1Dto = new EmployeeDto(1, 'Eve', 'Williams', 'eve.w@example.com', null, 1);
        $employee2Dto = new EmployeeDto(2, 'Bob', 'Johnson', 'bob.j@example.com', '111-222-333', 2);
        $employeeCollection = new EmployeeCollection([$employee1Dto, $employee2Dto]);

        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $event = new GetEmployeesQueryEvent();

        $employeeRepositoryMock->expects($this->once())
            ->method('findAllEmployees')
            ->willReturn($employeeCollection);

        $listener = new GetEmployeesQueryListener($employeeRepositoryMock);

        $listener($event);

        $this->assertEquals([$employee1Dto, $employee2Dto], $event->getEmployees()->toArray());
    }
}