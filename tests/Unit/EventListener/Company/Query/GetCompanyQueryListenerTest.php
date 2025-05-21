<?php

namespace App\Tests\Unit\EventListener\Company\Query;

use App\Dto\CompanyDto;
use App\Event\Complany\Query\GetCompanyQueryEvent;
use App\EventListener\Company\Query\GetCompanyQueryListener;
use App\Repository\CompanyRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetCompanyQueryListenerTest extends TestCase
{
    public function testOnGetCompanyQueryCompanyFound(): void
    {
        $companyId = 3;
        $companyDto = new CompanyDto(
            $companyId,
            'Found Company',
            '333',
            'Found Address',
            'Found City',
            '00-003'
        );

        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $event = new GetCompanyQueryEvent($companyId);

        $companyRepositoryMock->expects($this->once())
            ->method('findCompanyById')
            ->with($this->equalTo($companyId))
            ->willReturn($companyDto);

        $listener = new GetCompanyQueryListener($companyRepositoryMock);

        $listener($event);

        $this->assertEquals($companyDto, $event->getCompany());
    }

    public function testOnGetCompanyQueryCompanyNotFound(): void
    {
        $companyId = 8;

        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $event = new GetCompanyQueryEvent($companyId);

        $companyRepositoryMock->expects($this->once())
            ->method('findCompanyById')
            ->with($this->equalTo($companyId))
            ->willReturn(null);

        $listener = new GetCompanyQueryListener($companyRepositoryMock);

        $listener($event);

        $this->assertNull($event->getCompany());
    }
}