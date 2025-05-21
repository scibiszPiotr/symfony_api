<?php

namespace App\Factory;

use App\Entity\Company;
use App\Request\CompanyRequest;

class CompanyFactory
{
    public function createFromRequest(CompanyRequest $request): Company
    {
        $company = new Company();
        $this->updateFromRequest($company, $request);

        return $company;
    }

    public function updateFromRequest(Company $company, CompanyRequest $request): Company
    {
        $company->setName($request->name);
        $company->setNip($request->nip);
        $company->setAddress($request->address);
        $company->setCity($request->city);
        $company->setPostalCode($request->postalCode);

        return $company;
    }
}