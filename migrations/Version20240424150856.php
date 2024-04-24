<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424150856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C74F624B39D');
        $this->addSql('DROP INDEX IDX_E7927C74F624B39D ON email');
        $this->addSql('ALTER TABLE email ADD sender_first_name VARCHAR(255) NOT NULL, ADD sender_last_name VARCHAR(255) NOT NULL, ADD sender_email VARCHAR(255) NOT NULL, DROP sender_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email ADD sender_id INT NOT NULL, DROP sender_first_name, DROP sender_last_name, DROP sender_email');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C74F624B39D FOREIGN KEY (sender_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E7927C74F624B39D ON email (sender_id)');
    }
}
