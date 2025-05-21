<?php

namespace App\Tests\Unit\EventListener\Company\Query;

use App\Dto\CompanyCollection;
use App\Dto\CompanyDto;
use App\Event\Complany\Query\GetCompaniesQueryEvent;
use App\EventListener\Company\Query\GetCompaniesQueryListener;
use App\Repository\CompanyRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetCompaniesQueryListenerTest extends TestCase
{
    public function testOnGetCompaniesQuery(): void
    {
        $company1Dto = new CompanyDto(1, 'Company A', '111', 'Address A', 'City A', '00-001');
        $company2Dto = new CompanyDto(2, 'Company B', '222', 'Address B', 'City B', '00-002');
        $companyCollection = new CompanyCollection([$company1Dto, $company2Dto]);

        $companyRepositoryMock = $this->createMock(CompanyRepositoryInterface::class);
        $event = new GetCompaniesQueryEvent();

        $companyRepositoryMock->expects($this->once())
            ->method('findAllCompanies')
            ->willReturn($companyCollection);

        $listener = new GetCompaniesQueryListener($companyRepositoryMock);

        $listener($event);

        $this->assertEquals([$company1Dto, $company2Dto], $event->getCompanies()->toArray());
    }
}
