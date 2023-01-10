<?php

namespace App\MessageHandler;

use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Message\ProductCreator;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProductCreatorHandler
{
    public function __construct(
        private readonly ProductRepositoryPersist  $productRepositoryPersist,
        private readonly CategoryRepositoryRead    $categoryRepositoryRead,
        private readonly CategoryRepositoryPersist $categoryRepositoryPersist
    )
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ProductCreator $productCreator): void
    {
        $simpleXml = SimpleXML_Load_String($productCreator->getSimpleXmlAsString());

        $product = new Product();

        $product->setTitle($simpleXml->name);
        $product->setDescription(
            $simpleXml->description
            ?? $simpleXml->description_common
            ?? $simpleXml->description_for_ozon
            ?? $simpleXml->description_for_wildberries
        );
        $product->setWeight($simpleXml->weight);

        $category = $this->categoryRepositoryRead->getByName($simpleXml->category);

        if (is_null($category)){
            $category = new Category();
            $category->setName($simpleXml->category);

            $this->categoryRepositoryPersist->save($category);
        }

        $product->setCategory($category);

        $this->productRepositoryPersist->save($product);
    }
}