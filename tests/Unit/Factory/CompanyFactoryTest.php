<?php

namespace App\Tests\Unit\Factory;

use App\Entity\Company;
use App\Factory\CompanyFactory;
use App\Request\CompanyRequest;
use PHPUnit\Framework\TestCase;

class CompanyFactoryTest extends TestCase
{
    public function testCreateFromRequest(): void
    {
        $companyFactory = new CompanyFactory();
        $companyRequest = new CompanyRequest();
        $companyRequest->name = 'Test Company';
        $companyRequest->nip = '1234567890';
        $companyRequest->address = 'Test Address';
        $companyRequest->city = 'Test City';
        $companyRequest->postalCode = '00-000';

        $company = $companyFactory->createFromRequest($companyRequest);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertSame($companyRequest->name, $company->getName());
        $this->assertSame($companyRequest->nip, $company->getNip());
        $this->assertSame($companyRequest->address, $company->getAddress());
        $this->assertSame($companyRequest->city, $company->getCity());
        $this->assertSame($companyRequest->postalCode, $company->getPostalCode());
    }

    public function testUpdateFromRequest(): void
    {
        $companyFactory = new CompanyFactory();
        $company = new Company();
        $company->setName('Old Name');
        $company->setNip('123');
        $company->setAddress('Old Address');
        $company->setCity('Old City');
        $company->setPostalCode('Old Code');

        $companyRequest = new CompanyRequest();
        $companyRequest->name = 'New Name';
        $companyRequest->nip = 1234567890;
        $companyRequest->address = 'New Address';
        $companyRequest->city = 'New City';
        $companyRequest->postalCode = 'New Code';

        $updatedCompany = $companyFactory->updateFromRequest($company, $companyRequest);

        $this->assertSame($company, $updatedCompany);
        $this->assertSame($companyRequest->name, $updatedCompany->getName());
        $this->assertSame($companyRequest->nip, $updatedCompany->getNip());
        $this->assertSame($companyRequest->address, $updatedCompany->getAddress());
        $this->assertSame($companyRequest->city, $updatedCompany->getCity());
        $this->assertSame($companyRequest->postalCode, $updatedCompany->getPostalCode());
    }
}