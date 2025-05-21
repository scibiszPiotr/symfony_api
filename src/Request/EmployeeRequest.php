<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\Regex(pattern: '/^\+?[0-9\s\-]{7,20}$/', message: 'Niepoprawny numer telefonu')]
    public ?string $phone = null;

    #[Assert\NotBlank]
    #[Assert\Type('int')]
    public int $companyId;
}