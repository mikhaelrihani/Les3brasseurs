<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421123801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cooking_sheet RENAME INDEX idx_cbd6b5d2c4b1bf7b TO IDX_CBD6B5D236F1D7E9');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cooking_sheet RENAME INDEX idx_cbd6b5d236f1d7e9 TO IDX_CBD6B5D2C4B1BF7B');
    }
}
