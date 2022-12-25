<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221214101442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create products table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE products (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL, 
                weight integer NOT NULL, 
                description text NOT NULL, 
                category_id integer NOT NULL
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('products');
    }
}
