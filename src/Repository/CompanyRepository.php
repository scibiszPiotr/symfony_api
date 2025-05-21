<?php

namespace App\Repository;

use App\Dto\CompanyCollection;
use App\Dto\CompanyDto;
use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository implements CompanyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function findById(int $id): ?Company
    {
        return $this->find($id);
    }

    public function findCompanyById(int $id): ?CompanyDto
    {
        $qb = $this->createQueryBuilder('c')
            ->select(sprintf('NEW %s(c.id, c.name, c.nip, c.address, c.city, c.postalCode)', CompanyDto::class))
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        try {
            return $qb->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllCompanies(): CompanyCollection
    {
        $qb = $this->createQueryBuilder('c')
            ->select(sprintf('NEW %s(c.id, c.name, c.nip, c.address, c.city, c.postalCode)', CompanyDto::class))
            ->getQuery();

        return new CompanyCollection($qb->getResult());
    }
}