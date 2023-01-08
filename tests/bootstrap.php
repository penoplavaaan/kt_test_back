<?php

use App\Kernel;
use Doctrine\Deprecations\Deprecation;

//require_once __DIR__.'/../vendor/autoload.php';

//if (class_exists(Deprecation::class)) {
//    Deprecation::enableWithTriggerError();
//}

/**
 * @throws Exception
 */
function bootstrap(): void
{
    $kernel = new Kernel('test', true);
    $kernel->boot();

    $kernel->getContainer()
        ->get('doctrine')
        ->getConnection()
        ->query('CREATE TABLE products (
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