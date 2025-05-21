<?php

namespace App\Dto;


class CompanyDto implements \JsonSerializable
{
    public int $id;
    public string $name;
    public string $nip;
    public string $address;
    public string $city;
    public string $postalCode;

    public function __construct(
        int $id,
        string $name,
        string $nip,
        string $address,
        string $city,
        string $postalCode
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->nip = $nip;
        $this->address = $address;
        $this->city = $city;
        $this->postalCode = $postalCode;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nip' => $this->nip,
            'address' => $this->address,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
        ];
    }
}