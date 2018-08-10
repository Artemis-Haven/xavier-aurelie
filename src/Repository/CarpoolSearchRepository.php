<?php

namespace App\Repository;

use App\Entity\CarpoolSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarpoolSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarpoolSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarpoolSearch[]    findAll()
 * @method CarpoolSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpoolSearchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarpoolSearch::class);
    }

    public function findAllOrdered()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nbrOfSeats - c.reservedSeats ', 'DESC')
            ->addOrderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return CarpoolSearch[] Returns an array of CarpoolSearch objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarpoolSearch
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
