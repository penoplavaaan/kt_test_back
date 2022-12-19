<?php

namespace App\Handler;

use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Employee;
use App\Entity\ProductRepositoryPersist;
use App\Entity\ProductRepositoryRead;
use App\Message\UploadedFileProcessor;
use App\Resources\Product\ProductPaginatedResource;
use App\Util\PagerTrait;
use App\Util\Filterable;
use Symfony\Component\Messenger\MessageBusInterface;
use function Symfony\Component\String\b;

final class ProductCreate
{
    private $uploadPath;
    private MessageBusInterface $bus;

    public function __construct(
        UploadedFileProcessor $fileProcessor,
        ProductRepositoryPersist $productRepositoryPersist,
        CategoryRepositoryRead $categoryRepositoryRead,
        CategoryRepositoryPersist $categoryRepositoryPersist,
        MessageBusInterface $bus,
        $uploadPath
    )
    {
        $this->uploadPath = $uploadPath;
        $this->bus = $bus;
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function handle(string $fileName): void
    {
        $movedFilePath = $this->uploadPath.'/'.$fileName;

//        $this->bus->dispatch(new UploadedFileProcessor($movedFilePath));
    }
}