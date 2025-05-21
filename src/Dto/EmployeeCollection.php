<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;

class EmployeeCollection implements \IteratorAggregate, \Countable, \JsonSerializable
{
    private ArrayCollection $employees;

    public function __construct(array $employees = [])
    {
        $this->employees = new ArrayCollection($employees);
    }

    public function getIterator(): \Traversable
    {
        return $this->employees->getIterator();
    }

    public function count(): int
    {
        return $this->employees->count();
    }

    public function add(EmployeeDto $employeeDto): void
    {
        $this->employees->add($employeeDto);
    }

    public function toArray(): array
    {
        return $this->employees->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->employees->toArray();
    }
}