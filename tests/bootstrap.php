<?php

use App\Kernel;
use App\Tests\AppKernel;
use Doctrine\Deprecations\Deprecation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

require_once __DIR__.'/../vendor/autoload.php';

if (class_exists(Deprecation::class)) {
    Deprecation::enableWithTriggerError();
}

function bootstrap(): void
{
    $kernel = new Kernel('test', true);
    $kernel->boot();

    $application = new Application($kernel);
    $application->setAutoExit(false);

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:drop',
        '--if-exists' => '1',
        '--force' => '1'
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:create',
        '--if-not-exists' => '1',
    ]));

    $kernel->getContainer()
        ->get('doctrine')
        ->getConnection()
        ->executeQuery('CREATE TABLE products (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                weight integer NOT NULL,
                description text NOT NULL,
                category_id integer NOT NULL
            )');

    $kernel->getContainer()
        ->get('doctrine')
        ->getConnection()
        ->executeQuery('CREATE TABLE categories (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )');
    $kernel->getContainer()
        ->get('doctrine')
        ->getConnection()
        ->executeQuery('
            ALTER TABLE products
            ADD CONSTRAINT product_category_id_fkey
            FOREIGN KEY (category_id) REFERENCES categories(id);
        ');

}

bootstrap();