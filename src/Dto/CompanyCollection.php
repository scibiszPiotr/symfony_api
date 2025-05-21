<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;

class CompanyCollection implements \IteratorAggregate, \Countable, \JsonSerializable
{
    private ArrayCollection $companies;

    public function __construct(array $companies = [])
    {
        $this->companies = new ArrayCollection($companies);
    }

    public function getIterator(): \Traversable
    {
        return $this->companies->getIterator();
    }

    public function count(): int
    {
        return $this->companies->count();
    }

    public function add(CompanyDto $companyDto): void
    {
        $this->companies->add($companyDto);
    }

    public function toArray(): array
    {
        return $this->companies->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->companies->toArray();
    }
}