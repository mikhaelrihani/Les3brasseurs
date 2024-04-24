<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424161248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_receiver (email_id INT NOT NULL, receiver_id INT NOT NULL, INDEX IDX_36DE93DFA832C1C9 (email_id), INDEX IDX_36DE93DFCD53EDB6 (receiver_id), PRIMARY KEY(email_id, receiver_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiver (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_receiver ADD CONSTRAINT FK_36DE93DFA832C1C9 FOREIGN KEY (email_id) REFERENCES email (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_receiver ADD CONSTRAINT FK_36DE93DFCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_user DROP FOREIGN KEY FK_12A5F6CCA76ED395');
        $this->addSql('ALTER TABLE email_user DROP FOREIGN KEY FK_12A5F6CCA832C1C9');
        $this->addSql('DROP TABLE email_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_user (email_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_12A5F6CCA76ED395 (user_id), INDEX IDX_12A5F6CCA832C1C9 (email_id), PRIMARY KEY(email_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE email_user ADD CONSTRAINT FK_12A5F6CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_user ADD CONSTRAINT FK_12A5F6CCA832C1C9 FOREIGN KEY (email_id) REFERENCES email (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_receiver DROP FOREIGN KEY FK_36DE93DFA832C1C9');
        $this->addSql('ALTER TABLE email_receiver DROP FOREIGN KEY FK_36DE93DFCD53EDB6');
        $this->addSql('DROP TABLE email_receiver');
        $this->addSql('DROP TABLE receiver');
    }
}
