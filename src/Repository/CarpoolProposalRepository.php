<?php

namespace App\Repository;

use App\Entity\CarpoolProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarpoolProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarpoolProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarpoolProposal[]    findAll()
 * @method CarpoolProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpoolProposalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarpoolProposal::class);
    }

//    /**
//     * @return CarpoolProposal[] Returns an array of CarpoolProposal objects
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
    public function findOneBySomeField($value): ?CarpoolProposal
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
