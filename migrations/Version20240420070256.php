<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420070256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_user_infos DROP FOREIGN KEY FK_DB63D6A199A7B7E0');
        $this->addSql('ALTER TABLE users_user_infos DROP FOREIGN KEY FK_DB63D6A19D86650F');
        $this->addSql('DROP TABLE users_user_infos');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_user_infos (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, user_infos_id_id INT NOT NULL, UNIQUE INDEX UNIQ_DB63D6A19D86650F (user_id_id), UNIQUE INDEX UNIQ_DB63D6A199A7B7E0 (user_infos_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_user_infos ADD CONSTRAINT FK_DB63D6A199A7B7E0 FOREIGN KEY (user_infos_id_id) REFERENCES user_infos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users_user_infos ADD CONSTRAINT FK_DB63D6A19D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
