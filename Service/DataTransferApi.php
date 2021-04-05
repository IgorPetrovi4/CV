<?php


namespace App\Service;


use App\Entity\Jump;
use Doctrine\ORM\EntityManagerInterface;

class DataTransferApi
{
    /**
     * @var string
     */
    private $endpoint;
    private $em;

    public function __construct(EntityManagerInterface $em, string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->em = $em;
    }

    public function ApiTransfer()
    {
        $ch = file_get_contents($this->endpoint);
        $decoded = json_decode($ch, true);
        $countNewApi = 0;
        foreach ($decoded as $value) {
            $jump = $this->em->getRepository(Jump::class)->findOneBy(['apiId' => $value['id']]);
            if (!$jump) {
                $countNewApi++;
                $jump = new Jump();
            }
            $jump->setDescription($value['description']);
            $jump->setTitle($value['title']);
            $jump->setApiId($value['id']);
            $this->em->persist($jump);
        }
        $this->em->flush();
        return $countNewApi;
    }
}


