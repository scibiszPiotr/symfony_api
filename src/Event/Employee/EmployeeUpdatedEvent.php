<?php

namespace App\Event\Employee;

use App\Request\EmployeeRequest;
use Symfony\Contracts\EventDispatcher\Event;

class EmployeeUpdatedEvent extends Event
{
    public const NAME = 'employee.update';

    public function __construct(private int $employeeId, private EmployeeRequest $employeeRequest) {}

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function getEmployeeRequest(): EmployeeRequest
    {
        return $this->employeeRequest;
    }
}