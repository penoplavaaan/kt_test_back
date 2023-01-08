<?php

namespace App\Message;

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