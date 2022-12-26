<?php

namespace App\MessageHandler;

use App\Entity\Category;
use App\Entity\CategoryRepositoryPersist;
use App\Entity\CategoryRepositoryRead;
use App\Entity\Product;
use App\Entity\ProductRepositoryPersist;
use App\Message\ProductCreator;
use App\Message\UploadedFileProcessor;
use Exception;
use Prewk\XmlStringStreamer;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use XMLReader;

#[AsMessageHandler]
class UploadedFileProcessorHandler
{
    public function __construct(
        private readonly MessageBusInterface $bus
    )
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(UploadedFileProcessor $fileProcessor): void
    {
        $streamer = XmlStringStreamer::createStringWalkerParser($fileProcessor->getFilePath());

        while ($node = $streamer->getNode()) {
            $this->bus->dispatch(new ProductCreator($node));
        }
    }
}