<?php

namespace App\Repository;

use App\Entity\DigitalPaperList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DigitalPaperList|null find($id, $lockMode = null, $lockVersion = null)
 * @method DigitalPaperList|null findOneBy(array $criteria, array $orderBy = null)
 * @method DigitalPaperList[]    findAll()
 * @method DigitalPaperList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitalPaperListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalPaperList::class);
    }

    public function getPapersForFormat($idFormat, $idProduct)
    {
        return $this->createQueryBuilder('pl')
            ->where('pl.active = true')
            ->andWhere('pl.product = :idProduct')
            ->leftJoin('pl.digitalFormatLists', 'fl')
            ->andWhere('fl.id = :idFormat')
            ->setParameter('idFormat',$idFormat)
            ->setParameter('idProduct',$idProduct)
            ->getQuery()
            ->getResult()
            ;
    }


    /* public function getPapersForFormat($idFormat, $idProduct)
    {
        return $this->createQueryBuilder('pl')
            ->where('pl.active = true')
            ->leftJoin('pl.product', 'pr')
            ->andWhere('pr.id = :idProduct')
            ->leftJoin('pl.digitalFormatLists', 'fl')
            ->andWhere('fl.id = :idFormat')
            ->setParameter('idFormat',$idFormat)
            ->setParameter('idProduct',$idProduct)
            ->getQuery()
            ->getResult()
            ;
    }
*/
   /* public function getElementForFormat($idProduct, $format)
    {
        return $this->createQueryBuilder('d')
            ->where('d.product = :idProduct')
            ->andWhere('d.resolution >= :resolution')
            ->setParameter('idProduct',$idProduct)
            ->setParameter('resolution',$format)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }*/

    // /**
    //  * @return DigitalPaperList[] Returns an array of DigitalPaperList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DigitalPaperList
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
