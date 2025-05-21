<?php

namespace App\Event\Employee;

use Symfony\Contracts\EventDispatcher\Event;

class EmployeeDeletedEvent extends Event
{
    public const string NAME = 'employee.deleted';

    public function __construct(private int $employeeId) {
    }

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }
}