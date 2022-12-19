<?php

namespace App\Message;

use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Employee;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Entity\ProductRepositoryRead;
use SimpleXMLElement;
use XMLReader;

class UploadedFileProcessor
{
    public ?string $filePath;

    public function __construct(?string $filePath)
    {
        $this->filePath = $filePath;
    }
}