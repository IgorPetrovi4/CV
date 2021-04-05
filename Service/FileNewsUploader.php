<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileNewsUploader
{

    private $targetDirNews;
    private $slugger;

    public function __construct($targetDirNews, SluggerInterface $slugger)
    {
        $this->targetDirNews = $targetDirNews;
        $this->slugger = $slugger;
    }

    public function uploadNews(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirNews(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirNews()
    {
        return $this->targetDirNews;
    }
}