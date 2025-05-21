<?php

namespace App\Tests\Unit\EventListener\Company;

use App\Entity\Company;
use App\Event\Complany\UpdateCompanyEvent;
use App\EventListener\Company\UpdateCompanyListener;
use App\Factory\CompanyFactory;
use App\Repository\CompanyRepositoryInterface;
use App\Request\CompanyRequest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateCompanyListenerTest extends TestCase
{
    public function testOnUpdateCompanySuccess(): void
    {
        $companyId = 123;
        $company = new Company();
        $companyRequest = new CompanyRequest();
        $companyRequest->name = 'Updated Company';

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyFactoryMock = $this->createMock(CompanyFactory::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);

        $event = new UpdateCompanyEvent($companyId, $companyRequest);
        $violations = new ConstraintViolationList();

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($companyId))
            ->willReturn($company);

        $companyFactoryMock->expects($this->once())
            ->method('updateFromRequest')
            ->with($this->equalTo($company), $this->equalTo($companyRequest));

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($company))
            ->willReturn($violations);

        $entityManagerMock->expects($this->once())
            ->method('flush');

        $listener = new UpdateCompanyListener(
            $entityManagerMock,
            $validatorMock,
            $companyFactoryMock,
            $companyRepositoryMock
        );

        $listener($event);

        $this->addToAssertionCount(1);
    }

    public function testOnUpdateCompanyNotFound(): void
    {
        $companyId = 123;
        $companyRequest = new CompanyRequest();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyFactoryMock = $this->createMock(CompanyFactory::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);

        $event = new UpdateCompanyEvent($companyId, $companyRequest);

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($companyId))
            ->willReturn(null);

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new UpdateCompanyListener(
            $entityManagerMock,
            $validatorMock,
            $companyFactoryMock,
            $companyRepositoryMock
        );

        $this->expectException(NotFoundHttpException::class);

        $listener($event);
    }

    public function testOnUpdateCompanyWithValidationErrors(): void
    {
        $companyId = 123;
        $company = new Company();
        $companyRequest = new CompanyRequest();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $companyFactoryMock = $this->createMock(CompanyFactory::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);

        $event = new UpdateCompanyEvent($companyId, $companyRequest);
        $violations = new ConstraintViolationList();
        $violations->add($this->createMock(\Symfony\Component\Validator\ConstraintViolation::class));

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($companyId))
            ->willReturn($company);

        $companyFactoryMock->expects($this->once())
            ->method('updateFromRequest')
            ->with($this->equalTo($company), $this->equalTo($companyRequest));

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($company))
            ->willReturn($violations);

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new UpdateCompanyListener(
            $entityManagerMock,
            $validatorMock,
            $companyFactoryMock,
            $companyRepositoryMock
        );

        $this->expectException(\Exception::class);

        $listener($event);
    }
}
