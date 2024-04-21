<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421124923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dish_cooking_sheet (dish_id INT NOT NULL, cooking_sheet_id INT NOT NULL, INDEX IDX_570F017C148EB0CB (dish_id), INDEX IDX_570F017C1E4E09B9 (cooking_sheet_id), PRIMARY KEY(dish_id, cooking_sheet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dish_cooking_sheet ADD CONSTRAINT FK_570F017C148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_cooking_sheet ADD CONSTRAINT FK_570F017C1E4E09B9 FOREIGN KEY (cooking_sheet_id) REFERENCES cooking_sheet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dish_cooking_sheet DROP FOREIGN KEY FK_570F017C148EB0CB');
        $this->addSql('ALTER TABLE dish_cooking_sheet DROP FOREIGN KEY FK_570F017C1E4E09B9');
        $this->addSql('DROP TABLE dish_cooking_sheet');
    }
}
