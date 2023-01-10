<?php

declare(strict_types=1);

namespace App\Doctrine;

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

abstract class DoctrineRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
        private readonly Kernel $kernel
    )
    {
    }

    abstract function getEntityName(): string;

    /**
     * @return EntityManagerInterface
     */
    public function em(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param $entity
     * @return void
     */
    protected function persist($entity): void
    {

        $this->logger->critical('ENV '.$this->kernel->getEnvironment());
        $this->em()->persist($entity);
        $this->em()->flush($entity);
    }

    protected function repository(): EntityRepository|ObjectRepository
    {
        return $this->em()
            ->getRepository($this->getEntityName());
    }
}
