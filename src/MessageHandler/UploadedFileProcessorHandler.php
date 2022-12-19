<?php

namespace App\MessageHandler;

use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Message\UploadedFileProcessor;
use Exception;
use SimpleXMLElement;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use XMLReader;

#[AsMessageHandler]
class UploadedFileProcessorHandler
{
    /**
     * @throws Exception
     */
    public function __invoke(
        UploadedFileProcessor $fileProcessor,
        ProductRepositoryPersist $productRepositoryPersist,
        CategoryRepositoryRead $categoryRepositoryRead,
        CategoryRepositoryPersist $categoryRepositoryPersist,
    ): void
    {
        $reader = new XMLReader();
        $reader->open($fileProcessor->filePath);
        while($reader->read()) {
            if ($reader->nodeType !== XMLReader::ELEMENT) {
                continue;
            }

            if($reader->name == 'product') {
                $simpleXml = new SimpleXMLElement($reader->readOuterXml());

                $product = new Product();

                $product->setTitle($simpleXml->title);
                $product->setDescription($simpleXml->description);
//                $product->setWeight($simpleXml->weight);

                $category = $categoryRepositoryRead->getByName($simpleXml->category);

                if (is_null($category)){
                    $category = new Category();
                    $category->setName($simpleXml->category);

                    $categoryRepositoryPersist->save($category);
                }

                $product->setCategory($category);

                $productRepositoryPersist->save($product);
                dd($simpleXml);
            }
        }
    }
}