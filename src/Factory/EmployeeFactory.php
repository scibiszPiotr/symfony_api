<?php

namespace App\Factory;

use App\Entity\Employee;
use App\Request\EmployeeRequest;

class EmployeeFactory
{
    public function createFromRequest(EmployeeRequest $request): Employee
    {
        $employee = new Employee();
        $this->updateFromRequest($employee, $request);

        return $employee;
    }

    public function updateFromRequest(Employee $employee, EmployeeRequest $request): Employee
    {
        $employee->setFirstName($request->firstName);
        $employee->setLastName($request->lastName);
        $employee->setEmail($request->email);
        $employee->setPhone($request->phone);

        return $employee;
    }
}