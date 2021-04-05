<?php

namespace App\Repository;

use App\Entity\OrdersAll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrdersAll|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersAll|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersAll[]    findAll()
 * @method OrdersAll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersAllRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersAll::class);
    }

    public function getAllOrders($page, $limit, $user = null)
    {
        return $this->createQueryBuilder("o")
            ->where('o.user = ?1')
            ->andWhere('o.sales = ?2')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter(1,$user)
            ->setParameter(2,true);
    }


    public function getAllOrdersManagerDP()
    {
        return $this->createQueryBuilder("o")
            ->where('o.status IN (:id)')
            ->andWhere('o.article_number LIKE :DP')
            ->orderBy('o.id', 'DESC')
            ->setParameter('id',[3,4,5])
            ->setParameter('DP', 'DP%')
            ->getQuery()
            ->getResult();
    }


    public function getAllOrdersManagerAll()
    {
        return $this->createQueryBuilder("o")
            ->where('o.status IN (:id)')
            ->andWhere('o.article_number NOT LIKE :DP')
            ->orderBy('o.id', 'DESC')
            ->setParameter('id',[3,4,5])
            ->setParameter('DP', 'DP%')
            ->getQuery()
            ->getResult();
    }

    public function getSumPriceAllOrdersUser($user)
    {
        return$this->createQueryBuilder("o")
            ->select('SUM(o.price)')
            ->where('o.sales = ?1')
            ->andWhere('o.user = ?2')
            ->setParameter(1,1)
            ->setParameter(2,$user)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return OrdersAll[] Returns an array of OrdersAll objects
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

    /*
    public function findOneBySomeField($value): ?OrdersAll
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
