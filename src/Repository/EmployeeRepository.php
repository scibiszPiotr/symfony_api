<?php

namespace App\Repository;

use App\Dto\EmployeeCollection;
use App\Dto\EmployeeDto;
use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findById(int $id) : ?Employee
    {
        return $this->find($id);
    }

    public function findEmployeeById(int $id): ?EmployeeDto
    {
        $qb = $this->createQueryBuilder('e')
            ->select(sprintf('NEW %s(e.id, e.firstName, e.lastName, e.email, e.phone, c.id)', EmployeeDto::class))
            ->leftJoin('e.company', 'c')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        try {
            return $qb->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllEmployees(): EmployeeCollection
    {
        $qb = $this->createQueryBuilder('e')
            ->select(sprintf('NEW %s(e.id, e.firstName, e.lastName, e.email, e.phone, c.id)', EmployeeDto::class))
            ->leftJoin('e.company', 'c')
            ->getQuery();

        return new EmployeeCollection($qb->getResult());
    }
}