<?php

namespace App\Tests\Unit\EventListener\Company;

use App\Entity\Company;
use App\Event\Complany\CreateCompanyEvent;
use App\EventListener\Company\CreateCompanyListener;
use App\Factory\CompanyFactory;
use App\Request\CompanyRequest;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateCompanyListenerTest extends TestCase
{
    public function testOnCreateCompany(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $companyFactoryMock = $this->createMock(CompanyFactory::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);

        $companyRequest = new CompanyRequest();
        $companyRequest->name = 'Test Company';
        $companyRequest->nip = '1234567890';

        $company = new Company();
        $company->setName($companyRequest->name);

        $event = new CreateCompanyEvent($companyRequest);

        $companyFactoryMock->expects($this->once())
            ->method('createFromRequest')
            ->with($this->equalTo($companyRequest))
            ->willReturn($company);

        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($company))
            ->willReturn(new ConstraintViolationList());

        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($company));

        $entityManagerMock->expects($this->once())
            ->method('flush');

        $listener = new CreateCompanyListener($entityManagerMock, $validatorMock, $companyFactoryMock);
        $listener($event);

        $this->doesNotPerformAssertions();
    }

    public function testOnCreateCompanyWithValidationErrors(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $companyFactoryMock = $this->createMock(CompanyFactory::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);

        $companyRequest = new CompanyRequest();

        $company = new Company();

        $event = new CreateCompanyEvent($companyRequest);

        $companyFactoryMock->expects($this->once())
            ->method('createFromRequest')
            ->willReturn($company);

        $violations = new ConstraintViolationList();
        $violation1 = $this->createMock(ConstraintViolation::class);
        $violation1->method('getMessage')->willReturn('The name should not be blank.');
        $violations->add($violation1);
        $validatorMock->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $entityManagerMock->expects($this->never())
            ->method('persist');
        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new CreateCompanyListener($entityManagerMock, $validatorMock, $companyFactoryMock);

        $this->expectException(Exception::class);
        $listener($event);
    }
}