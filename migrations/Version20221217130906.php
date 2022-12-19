<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221217130906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add foreign key for products';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE products
            ADD CONSTRAINT product_category_id_fkey
            FOREIGN KEY (category_id) REFERENCES categories(id);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE products
            DROP CONSTRAINT product_category_id_fkey
        ');
    }
}
