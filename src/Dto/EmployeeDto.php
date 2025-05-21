<?php

namespace App\Dto;

class EmployeeDto implements \JsonSerializable
{
    public int $id;

    public string $firstName;

    public string $lastName;

    public string $email;

    public ?string $phone = null;

    public int $companyId;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        ?string $phone,
        int $companyId
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->companyId = $companyId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'companyId' => $this->companyId,
        ];
    }
}