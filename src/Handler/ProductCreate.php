<?php

namespace App\Handler;

use App\Message\UploadedFileProcessor;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProductCreate
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private $uploadPath,
    )
    {
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function handle(string $fileName): void
    {
        $movedFilePath = $this->uploadPath . '/' . $fileName;

        $this->bus->dispatch(new UploadedFileProcessor($movedFilePath));
    }
}