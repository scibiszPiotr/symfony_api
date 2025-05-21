<?php

namespace App\Event\Employee\Query;

use App\Dto\EmployeeCollection;
use Symfony\Contracts\EventDispatcher\Event;

class GetEmployeesQueryEvent extends Event
{
    public const NAME = 'employees.query.get_all';

    private EmployeeCollection $employees;

    public function getEmployees(): EmployeeCollection
    {
        return $this->employees;
    }

    public function setEmployees(EmployeeCollection $employees): void
    {
        $this->employees = $employees;
    }
}