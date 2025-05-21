<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CompanyRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{10}$/', message: 'NIP musi składać się z 10 cyfr')]
    public int $nip;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $address;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $city;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{2}-\d{3}$/', message: 'Kod pocztowy musi być w formacie XX-XXX')]
    public string $postalCode;
}
