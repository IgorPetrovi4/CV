<?php


namespace App\Service;


use App\Entity\DbalConfig;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;


class ConnectorRemoteDb
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function ConnectorRemoteDb($resourse)
    {
        $Dbal = $this->em->getRepository(DbalConfig::class)->findOneBy(['resourse'=>$resourse])->getUrlDbal();
        $connectionParams = array('url' => $Dbal);
        return $conn = DriverManager::getConnection($connectionParams);
    }
}