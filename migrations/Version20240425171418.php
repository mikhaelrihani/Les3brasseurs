<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425171418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_infos_group (user_infos_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_8B236D72B4C7A8CA (user_infos_id), INDEX IDX_8B236D72FE54D947 (group_id), PRIMARY KEY(user_infos_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_infos_group ADD CONSTRAINT FK_8B236D72B4C7A8CA FOREIGN KEY (user_infos_id) REFERENCES user_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_infos_group ADD CONSTRAINT FK_8B236D72FE54D947 FOREIGN KEY (group_id) REFERENCES `Group` (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE job');
        $this->addSql('ALTER TABLE user_infos ADD job VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_infos_group DROP FOREIGN KEY FK_8B236D72B4C7A8CA');
        $this->addSql('ALTER TABLE user_infos_group DROP FOREIGN KEY FK_8B236D72FE54D947');
        $this->addSql('DROP TABLE user_infos_group');
        $this->addSql('ALTER TABLE user_infos DROP job');
    }
}
