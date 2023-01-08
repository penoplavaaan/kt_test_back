<?php

namespace App\MessageHandler;

use App\Message\ProductCreator;
use App\Message\UploadedFileProcessor;
use Exception;
use Prewk\XmlStringStreamer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

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

        $filesystem = new Filesystem();
        $filesystem->remove($fileProcessor->getFilePath());
    }
}