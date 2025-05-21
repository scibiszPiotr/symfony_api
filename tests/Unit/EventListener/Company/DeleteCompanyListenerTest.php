<?php

namespace App\Tests\Unit\EventListener\Company;

use App\Entity\Company;
use App\Event\Complany\DeleteCompanyEvent;
use App\EventListener\Company\DeleteCompanyListener;
use App\Repository\CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteCompanyListenerTest extends TestCase
{
    public function testOnDeleteCompanySuccess(): void
    {
        $companyId = 1;
        $company = new Company();

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);

        $event = new DeleteCompanyEvent($companyId);

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($companyId))
            ->willReturn($company);

        $entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($company));

        $entityManagerMock->expects($this->once())
            ->method('flush');

        $listener = new DeleteCompanyListener($entityManagerMock, $companyRepositoryMock);

        $listener($event);

        $this->addToAssertionCount(1);
    }

    public function testOnDeleteCompanyNotFound(): void
    {
        $companyId = 2;

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);

        $event = new DeleteCompanyEvent($companyId);

        $companyRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($companyId))
            ->willReturn(null);

        $entityManagerMock->expects($this->never())
            ->method('remove');

        $entityManagerMock->expects($this->never())
            ->method('flush');

        $listener = new DeleteCompanyListener($entityManagerMock, $companyRepositoryMock);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Company with id %d not found.', $companyId));

        $listener($event);
    }
}