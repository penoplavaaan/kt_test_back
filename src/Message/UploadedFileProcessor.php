<?php

namespace App\Message;

class UploadedFileProcessor
{
    private ?string $filePath;

    public function __construct(?string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }
}