<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param $value
     * @return Order[] Returns an array of Order objects
     */

    public function findByClient($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.client = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $value
     * @return Order[] Returns an array of Order objects
     * @throws \Exception
     */

    public function findByStatus(String $value)
    {

        $d = new \DateTime();
        $d->setTime(0, 0, 0,0);
        $d = new \DateTimeImmutable($d->format('Y-m-d\TH:i:s.u'));

        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :val')
            ->setParameter('val', $value)
            ->andWhere('o.createdAt >= :dat')
            ->setParameter('dat', $d)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
