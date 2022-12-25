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

class ProductCreator
{
    private string $simpleXmlAsString;

    public function __construct(string $simpleXml)
    {
        $this->simpleXmlAsString = $simpleXml;
    }

    public function getSimpleXmlAsString(): string
    {
        return $this->simpleXmlAsString;
    }
}