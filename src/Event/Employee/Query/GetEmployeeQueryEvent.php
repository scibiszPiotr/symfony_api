<?php

namespace App\Event\Employee\Query;

use App\Dto\EmployeeDto;
use Symfony\Contracts\EventDispatcher\Event;

class GetEmployeeQueryEvent extends Event
{
    public const NAME = 'employee.query.get_one';

    private ?EmployeeDto $employee = null;

    public function __construct(private int $employeeId) {}

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function getEmployee(): ?EmployeeDto
    {
        return $this->employee;
    }

    public function setEmployee(?EmployeeDto $employee): void
    {
        $this->employee = $employee;
    }
}