<?php

namespace App\Repository;

use App\Entity\CarpoolAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarpoolAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarpoolAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarpoolAnswer[]    findAll()
 * @method CarpoolAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpoolAnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarpoolAnswer::class);
    }

//    /**
//     * @return CarpoolAnswer[] Returns an array of CarpoolAnswer objects
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
    public function findOneBySomeField($value): ?CarpoolAnswer
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
