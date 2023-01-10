<?php

namespace App\Handler;

use App\Kernel;
use App\Message\UploadedFileProcessor;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProductCreate
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private                              $uploadPath,
        private readonly LoggerInterface     $logger,
        private readonly Kernel $kernel
    )
    {
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function handle(string $fileName): void
    {
        $this->logger->critical('ENV IN PRODUCT_CREATE'.$this->kernel->getEnvironment());

        $movedFilePath = $this->uploadPath.'/'.$fileName;

        $this->bus->dispatch(new UploadedFileProcessor($movedFilePath));
    }
}