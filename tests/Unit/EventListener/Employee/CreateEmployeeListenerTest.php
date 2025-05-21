<?php

namespace App\Tests\Unit\EventListener\Employee;

use App\Entity\Company;
use App\Entity\Employee;
use App\Event\Employee\EmployeeCreatedEvent;
use App\EventListener\Employee\CreateEmployeeListener;
use App\Factory\EmployeeFactory;
use App\Repository\CompanyRepositoryInterface;
use App\Request\EmployeeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateEmployeeListenerTest extends TestCase
{
    public function testOnEmployeeCreatedSuccess(): void
    {
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = 'Charlie';
        $employeeRequest->lastName = 'Brown';
        $employeeRequest->email = 'charlie.b@example.com';
        $employeeRequest->companyId = 5;

        $company = new Company();

        $employee = new Employee();
        $employee->setFirstName($employeeRequest->firstName);
        $employee->setLastName($employeeRequest->lastName);
        $employee->setEmail($employeeRequest->email);
        $employee->setCompany($company);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeCreatedEvent($employeeRequest);
        $violations = new ConstraintViolationList();

        $employeeFactoryMock->expects($this->once())
            ->method('createFromRequest')
            ->with($this->equalTo($employeeRequest))
            ->willReturn($employee);

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeRequest->companyId))
            ->willReturn($company);

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($employee))
            ->willReturn($violations);

        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($employee));

        $entityManagerMock->expects($this->once())
            ->method('flush');

        $listener = new CreateEmployeeListener(
            $entityManagerMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $listener($event);

        $this->addToAssertionCount(1);
    }

    public function testOnEmployeeCreatedCompanyNotFound(): void
    {
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = 'Lucy';
        $employeeRequest->lastName = 'Van Pelt';
        $employeeRequest->email = 'lucy.v@example.com';
        $employeeRequest->companyId = 99;

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeCreatedEvent($employeeRequest);

        $employeeFactoryMock->expects($this->once())
            ->method('createFromRequest')
            ->with($this->equalTo($employeeRequest))
            ->willReturn(new Employee());

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeRequest->companyId))
            ->willReturn(null);

        $entityManagerMock->expects($this->never())
            ->method('persist');
        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new CreateEmployeeListener(
            $entityManagerMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf('Company with id %d not found.', $employeeRequest->companyId));

        $listener($event);
    }

    public function testOnEmployeeCreatedWithValidationErrors(): void
    {
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = '';
        $employeeRequest->lastName = 'Test';
        $employeeRequest->email = 'invalid-email';
        $employeeRequest->companyId = 1;

        $company = new Company();
        $employee = new Employee();
        $employee->setCompany($company);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeCreatedEvent($employeeRequest);
        $violations = new ConstraintViolationList();
        $violations->add($this->createMock(ConstraintViolation::class));
        $violations->add($this->createMock(ConstraintViolation::class));

        $employeeFactoryMock->expects($this->once())
            ->method('createFromRequest')
            ->with($this->equalTo($employeeRequest))
            ->willReturn($employee);

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeRequest->companyId))
            ->willReturn($company);

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($employee))
            ->willReturn($violations);

        $entityManagerMock->expects($this->never())
            ->method('persist');
        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new CreateEmployeeListener(
            $entityManagerMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Validation errors: ');

        $listener($event);
    }
}