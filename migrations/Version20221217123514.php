<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221217123514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create categories table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE categories (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('categories');
    }
}
