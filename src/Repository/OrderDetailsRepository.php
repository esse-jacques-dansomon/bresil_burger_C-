<?php

namespace App\Repository;

use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetails[]    findAll()
 * @method OrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetails::class);
    }

    // /**
    //  * @return OrderDetails[] Returns an array of OrderDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneBySomeField()
    {
        $d = new \DateTime();
        $d->setTime(0, 0, 0,0);
        $d = new \DateTimeImmutable($d->format('Y-m-d\TH:i:s.u'));
        return $this->createQueryBuilder('o')
            ->addGroupBy('o.quantity')
            ->getQuery()
            ->getResult()
        ;
    }

}
