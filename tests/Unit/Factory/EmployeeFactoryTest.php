<?php

namespace App\Tests\Unit\Factory;

use App\Entity\Employee;
use App\Factory\EmployeeFactory;
use App\Request\EmployeeRequest;
use PHPUnit\Framework\TestCase;

class EmployeeFactoryTest extends TestCase
{
    public function testCreateFromRequest(): void
    {
        $employeeFactory = new EmployeeFactory();
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = 'John';
        $employeeRequest->lastName = 'Doe';
        $employeeRequest->email = 'john.doe@example.com';
        $employeeRequest->phone = '+48 123 456 789';
        $employeeRequest->companyId = 1;

        $employee = $employeeFactory->createFromRequest($employeeRequest);

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertSame($employeeRequest->firstName, $employee->getFirstName());
        $this->assertSame($employeeRequest->lastName, $employee->getLastName());
        $this->assertSame($employeeRequest->email, $employee->getEmail());
        $this->assertSame($employeeRequest->phone, $employee->getPhone());
    }

    public function testUpdateFromRequest(): void
    {
        $employeeFactory = new EmployeeFactory();
        $employee = new Employee();
        $employee->setFirstName('Old John');
        $employee->setLastName('Old Doe');
        $employee->setEmail('old.john@example.com');
        $employee->setPhone('Old Phone');

        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = 'New John';
        $employeeRequest->lastName = 'New Doe';
        $employeeRequest->email = 'new.john@example.com';
        $employeeRequest->phone = 'New Phone';
        $employeeRequest->companyId = 2;

        $updatedEmployee = $employeeFactory->updateFromRequest($employee, $employeeRequest);

        $this->assertSame($employee, $updatedEmployee);
        $this->assertSame($employeeRequest->firstName, $updatedEmployee->getFirstName());
        $this->assertSame($employeeRequest->lastName, $updatedEmployee->getLastName());
        $this->assertSame($employeeRequest->email, $updatedEmployee->getEmail());
        $this->assertSame($employeeRequest->phone, $updatedEmployee->getPhone());
        $this->assertNull($updatedEmployee->getCompany());
    }
}
