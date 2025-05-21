<?php

namespace App\Tests\Unit\EventListener\Employee\Query;

use App\Dto\EmployeeDto;
use App\Event\Employee\Query\GetEmployeeQueryEvent;
use App\EventListener\Employee\Query\GetEmployeeQueryListener;
use App\Repository\EmployeeRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetEmployeeQueryListenerTest extends TestCase
{
    public function testOnGetEmployeeQueryEmployeeFound(): void
    {
        $employeeId = 15;
        $employeeDto = new EmployeeDto(
            $employeeId,
            'Alice',
            'Smith',
            'alice.smith@example.com',
            null,
            3
        );

        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $event = new GetEmployeeQueryEvent($employeeId);

        $employeeRepositoryMock->expects($this->once())
            ->method('findEmployeeById')
            ->with($this->equalTo($employeeId))
            ->willReturn($employeeDto);

        $listener = new GetEmployeeQueryListener($employeeRepositoryMock);

        $listener($event);

        $this->assertEquals($employeeDto, $event->getEmployee());
    }

    public function testOnGetEmployeeQueryEmployeeNotFound(): void
    {
        $employeeId = 22;

        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $event = new GetEmployeeQueryEvent($employeeId);

        $employeeRepositoryMock->expects($this->once())
            ->method('findEmployeeById')
            ->with($this->equalTo($employeeId))
            ->willReturn(null);

        $listener = new GetEmployeeQueryListener($employeeRepositoryMock);

        $listener($event);

        $this->assertNull($event->getEmployee());
    }
}