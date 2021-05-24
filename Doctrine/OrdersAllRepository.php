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

    public function getCountUserOrders($user = null)
    {
        return $this->createQueryBuilder("o")
            ->select('COUNT(o.id)')
            ->where('o.user = ?1')
            ->andWhere('o.sales = ?2')
            ->setParameter(1,$user)
            ->setParameter(2,true)
            ->getQuery()
            ->getResult();
    }

    public function getAllOrdersUsersManager($page, $limit, $users = [], $search =null, $date_start = null, $date_end =null )
    {
        return $this->createQueryBuilder("o")
            ->andWhere('o.user IN (:users)')
            ->andWhere('o.sales = ?2')
            ->andWhere('o.datetime >= :date_start')
            ->andWhere('o.datetime <= :date_end')
            ->andWhere('o.description LIKE :search OR o.article_number LIKE :search')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter('users',$users)
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('date_start', $date_start)
            ->setParameter('date_end', $date_end)
            ->setParameter(2,true)
            ->getQuery()
            ->getResult();
    }

    public function getNewPosOrdersUsersManager($page, $limit, $users = [])
    {
        return $this->createQueryBuilder("o")
            ->where('o.user IN (:users)')
            ->andWhere('o.delivery IS NOT NULL')
            ->andWhere('o.delivery LIKE :OF')
            ->andWhere('o.sales = ?1')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setParameter('1',true)
            ->setParameter('users',$users)
            ->setParameter('OF', '%Відділення%')
            ->getQuery()
            ->getResult();
    }

    public function getDeliveryOrdersUsersManager($page, $limit, $users = [])
    {
        return $this->createQueryBuilder("o")
            ->where('o.user IN (:users)')
            ->andWhere('o.delivery IS NOT NULL')
            ->andWhere('o.delivery NOT LIKE :OF')
            ->andWhere('o.delivery NOT LIKE :SV')
            ->andWhere('o.sales = ?1')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setParameter('1',true)
            ->setParameter('users',$users)
            ->setParameter('OF', '%Відділення%')
            ->setParameter('SV', '%Самовывоз%')
            ->getQuery()
            ->getResult();
    }



    public function getAllOrdersManagerDP()
    {
        return $this->createQueryBuilder("o")
            ->where('o.status IN (:id)')
            ->andWhere('o.article_number LIKE :DP OR o.article_number LIKE :MO')
            ->orderBy('o.id', 'DESC')
            ->setParameter('id',[3,4,5])
            ->setParameter('DP', 'DP%')
            ->setParameter('MO', 'MO%')
            ->getQuery()
            ->getResult();
    }


    public function getAllOrdersManagerAll()
    {
        return $this->createQueryBuilder("o")
            ->where('o.status IN (:id)')
            ->andWhere('o.article_number NOT LIKE :DP OR o.article_number LIKE :MO')
            ->orderBy('o.id', 'DESC')
            ->setParameter('id',[3,4,5])
            ->setParameter('DP', 'DP%')
            ->setParameter('MO', 'MO%')
            ->getQuery()
            ->getResult();
    }


    public function getSumCreditUser($user)
    {
        return$this->createQueryBuilder("o")
            ->select('SUM(o.price)')
            ->where('o.paid IS NULL')
            ->andWhere('o.user = ?2')
            ->setParameter(2,$user)
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


    public function getRoleOrders($role)
    {
        return $this->createQueryBuilder("o")
            ->where('o.refill IS NULL')
            ->andWhere('o.sales = ?1')
            ->leftJoin('o.user', 'u')
            ->orderBy('o.id', 'DESC')
            ->andWhere('u.roles  LIKE :roles')
            ->setParameter('1',true)
            ->setParameter('roles','%"'.$role.'"%')
            ->getQuery()
            ->getResult();

    }


    public function getClientOrders($role)
    {
        return $this->createQueryBuilder("o")
            ->where('o.refill IS NULL')
            ->andWhere('o.user IS NULL')
            ->orderBy('o.id', 'DESC')
            ->leftJoin('o.user', 'u')
            ->orWhere('u.roles  LIKE :roles')
            ->setParameter('roles','%"'.$role.'"%')
            ->getQuery()
            ->getResult();
    }

    public function getNewPosOrders()
    {
        return $this->createQueryBuilder("o")
            ->where('o.delivery IS NOT NULL')
            ->andWhere('o.delivery LIKE :OF')
            ->andWhere('o.sales = ?1')
            ->orderBy('o.id', 'DESC')
            ->setParameter('1',true)
            ->setParameter('OF', '%Відділення%')
            ->getQuery()
            ->getResult();
    }

    public function getDeliveryOrders()
    {
        return $this->createQueryBuilder("o")
            ->andWhere('o.delivery IS NOT NULL')
            ->andWhere('o.delivery NOT LIKE :OF')
            ->andWhere('o.delivery NOT LIKE :SV')
            ->andWhere('o.sales = ?1')
            ->orderBy('o.id', 'DESC')
            ->setParameter('1',true)
            ->setParameter('OF', '%Відділення%')
            ->setParameter('SV', '%Самовывоз%')
            ->getQuery()
            ->getResult();
    }

}
