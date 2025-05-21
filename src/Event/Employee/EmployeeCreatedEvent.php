<?php

namespace App\Event\Employee;

use App\Request\EmployeeRequest;
use Symfony\Contracts\EventDispatcher\Event;

class EmployeeCreatedEvent extends Event
{
    public const NAME = 'employee.create';

    public function __construct(private EmployeeRequest $employeeRequest) {}

    public function getEmployeeRequest(): EmployeeRequest
    {
        return $this->employeeRequest;
    }
}