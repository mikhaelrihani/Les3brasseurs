<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520120325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C74B897366B');
        $this->addSql('ALTER TABLE email_file DROP FOREIGN KEY FK_13A9169593CB796C');
        $this->addSql('ALTER TABLE email_file DROP FOREIGN KEY FK_13A91695A832C1C9');
        $this->addSql('ALTER TABLE email_receiver DROP FOREIGN KEY FK_36DE93DFA832C1C9');
        $this->addSql('ALTER TABLE email_receiver DROP FOREIGN KEY FK_36DE93DFCD53EDB6');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE email_file');
        $this->addSql('DROP TABLE email_receiver');
        $this->addSql('DROP TABLE receiver');
        $this->addSql('ALTER TABLE date CHANGE month month INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C9F36105E237E06 ON file (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, date_id INT NOT NULL, object VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, delivered TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, sender_first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, sender_last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, sender_email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E7927C74B897366B (date_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE email_file (email_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_13A91695A832C1C9 (email_id), INDEX IDX_13A9169593CB796C (file_id), PRIMARY KEY(email_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE email_receiver (email_id INT NOT NULL, receiver_id INT NOT NULL, INDEX IDX_36DE93DFA832C1C9 (email_id), INDEX IDX_36DE93DFCD53EDB6 (receiver_id), PRIMARY KEY(email_id, receiver_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE receiver (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C74B897366B FOREIGN KEY (date_id) REFERENCES date (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE email_file ADD CONSTRAINT FK_13A9169593CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_file ADD CONSTRAINT FK_13A91695A832C1C9 FOREIGN KEY (email_id) REFERENCES email (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_receiver ADD CONSTRAINT FK_36DE93DFA832C1C9 FOREIGN KEY (email_id) REFERENCES email (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_receiver ADD CONSTRAINT FK_36DE93DFCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date CHANGE month month VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8C9F36105E237E06 ON file');
    }
}
