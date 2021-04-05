<?php


namespace App\Service;


use App\Repository\JumpRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;

class ScreenshotGenerator
{

    private $em;
    private $jumpRepository;
    private $urlScreenshot;
    private $numberOfRecords;
    private $pathFile;

    public function __construct(EntityManagerInterface $em, JumpRepository $jumpRepository, string $urlScreenshot, $numberOfRecords, string $pathFile)
    {
        $this->em = $em;
        $this->jumpRepository = $jumpRepository;
        $this->urlScreenshot = $urlScreenshot;
        $this->numberOfRecords = $numberOfRecords;
        $this->pathFile = $pathFile;
    }

    public function generate()
    {
        do {
            $dataArray = $this->jumpRepository->findBy(['date' => null], [], $this->numberOfRecords, 0);
            foreach ($dataArray as $jump) {
                $url = sprintf($this->urlScreenshot, $jump->getApiId());
                $file = $jump->getApiId() . '.png';
                $screenshot = new Process(['wkhtmltoimage', '-n', '--width', '1024', '--height', '768', '--format', 'png', $url, $this->pathFile . $file]);
                $screenshot->run();
                $jump->setDate(new DateTimeImmutable());
            }
            $this->em->flush();
            sleep(5);
        } while (true);
    }
}