<?php

namespace App\Tests\Unit\EventListener\Employee;

use App\Entity\Company;
use App\Entity\Employee;
use App\Event\Employee\EmployeeUpdatedEvent;
use App\EventListener\Employee\UpdateEmployeeListener;
use App\Factory\EmployeeFactory;
use App\Repository\CompanyRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;
use App\Request\EmployeeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateEmployeeListenerTest extends TestCase
{
    public function testOnEmployeeUpdatedSuccess(): void
    {
        $employeeId = 42;
        $employee = new Employee();
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = 'Updated First Name';
        $employeeRequest->companyId = 6;
        $company = new Company();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeUpdatedEvent($employeeId, $employeeRequest);
        $violations = new ConstraintViolationList();

        $employeeRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeId))
            ->willReturn($employee);

        $employeeFactoryMock->expects($this->once())
            ->method('updateFromRequest')
            ->with($this->equalTo($employee), $this->equalTo($employeeRequest));

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeRequest->companyId))
            ->willReturn($company);

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($employee))
            ->willReturn($violations);

        $entityManagerMock->expects($this->once())
            ->method('flush');

        $listener = new UpdateEmployeeListener(
            $entityManagerMock,
            $employeeRepositoryMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $listener($event);

        $this->addToAssertionCount(1);
    }

    public function testOnEmployeeUpdatedEmployeeNotFound(): void
    {
        $employeeId = 55;
        $employeeRequest = new EmployeeRequest();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeUpdatedEvent($employeeId, $employeeRequest);

        $employeeRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeId))
            ->willReturn(null);

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new UpdateEmployeeListener(
            $entityManagerMock,
            $employeeRepositoryMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Employee with id %d not found.', $employeeId));

        $listener($event);
    }

    public function testOnEmployeeUpdatedCompanyNotFound(): void
    {
        $employeeId = 61;
        $employee = new Employee();
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = 'Test Employee';
        $employeeRequest->companyId = 99;

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeUpdatedEvent($employeeId, $employeeRequest);

        $employeeRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeId))
            ->willReturn($employee);

        $employeeFactoryMock->expects($this->once())
            ->method('updateFromRequest')
            ->with($this->equalTo($employee), $this->equalTo($employeeRequest));

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeRequest->companyId))
            ->willReturn(null);

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new UpdateEmployeeListener(
            $entityManagerMock,
            $employeeRepositoryMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf('Company with id %d not found.', $employeeRequest->companyId));

        $listener($event);
    }

    public function testOnEmployeeUpdatedWithValidationErrors(): void
    {
        $employeeId = 73;
        $employee = new Employee();
        $employeeRequest = new EmployeeRequest();
        $employeeRequest->firstName = '';
        $employeeRequest->companyId = 1;
        $company = new Company();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $employeeRepositoryMock = $this->createMock(EmployeeRepositoryInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $employeeFactoryMock = $this->createMock(EmployeeFactory::class);

        $event = new EmployeeUpdatedEvent($employeeId, $employeeRequest);
        $violations = new ConstraintViolationList();
        $violations->add($this->createMock(ConstraintViolation::class));

        $employeeRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeId))
            ->willReturn($employee);

        $employeeFactoryMock->expects($this->once())
            ->method('updateFromRequest')
            ->with($this->equalTo($employee), $this->equalTo($employeeRequest));

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($employeeRequest->companyId))
            ->willReturn($company);

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($employee))
            ->willReturn($violations);

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new UpdateEmployeeListener(
            $entityManagerMock,
            $employeeRepositoryMock,
            $validatorMock,
            $companyRepositoryMock,
            $employeeFactoryMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Validation errors: ');

        $listener($event);
    }
}