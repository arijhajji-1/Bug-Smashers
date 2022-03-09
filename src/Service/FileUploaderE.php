<?php

// src/Service/FileUploaderE.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderE
{
    private $targetDirectory;

    public function __construct($targetDirectoryE)
    {
        $this->targetDirectory = $targetDirectoryE;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}