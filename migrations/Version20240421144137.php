<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421144137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C74C5B476DB');
        $this->addSql('DROP INDEX IDX_E7927C74C5B476DB ON email');
        $this->addSql('ALTER TABLE email CHANGE date_id_id date_id INT NOT NULL');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C74B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('CREATE INDEX IDX_E7927C74B897366B ON email (date_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C74B897366B');
        $this->addSql('DROP INDEX IDX_E7927C74B897366B ON email');
        $this->addSql('ALTER TABLE email CHANGE date_id date_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C74C5B476DB FOREIGN KEY (date_id_id) REFERENCES date (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E7927C74C5B476DB ON email (date_id_id)');
    }
}
