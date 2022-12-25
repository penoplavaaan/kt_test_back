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
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use function Symfony\Component\String\b;

final class ProductCreate
{
    public function __construct(
        private MessageBusInterface $bus,
        private $uploadPath
    )
    {
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function handle(string $fileName): void
    {
        $movedFilePath = $this->uploadPath.'/'.$fileName;

        $this->bus->dispatch(new UploadedFileProcessor($movedFilePath));
    }
}