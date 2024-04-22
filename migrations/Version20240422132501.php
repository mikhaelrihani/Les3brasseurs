<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422132501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `Group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cooking_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cooking_sheet (id INT AUTO_INCREMENT NOT NULL, cooking_categories_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CBD6B5D236F1D7E9 (cooking_categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cooking_sheet_picture (cooking_sheet_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_E44996A1E4E09B9 (cooking_sheet_id), INDEX IDX_E44996AEE45BDBF (picture_id), PRIMARY KEY(cooking_sheet_id, picture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, month VARCHAR(255) NOT NULL, day INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, comment VARCHAR(1000) NOT NULL, help_url VARCHAR(255) NOT NULL, help_text VARCHAR(1000) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish_picture (dish_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_866C551F148EB0CB (dish_id), INDEX IDX_866C551FEE45BDBF (picture_id), PRIMARY KEY(dish_id, picture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish_cooking_sheet (dish_id INT NOT NULL, cooking_sheet_id INT NOT NULL, INDEX IDX_570F017C148EB0CB (dish_id), INDEX IDX_570F017C1E4E09B9 (cooking_sheet_id), PRIMARY KEY(dish_id, cooking_sheet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, date_id INT NOT NULL, sender_id INT NOT NULL, object VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, delivered TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E7927C74B897366B (date_id), INDEX IDX_E7927C74F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_user (email_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_12A5F6CCA832C1C9 (email_id), INDEX IDX_12A5F6CCA76ED395 (user_id), PRIMARY KEY(email_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_file (email_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_13A91695A832C1C9 (email_id), INDEX IDX_13A9169593CB796C (file_id), PRIMARY KEY(email_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, mime_id INT NOT NULL, name VARCHAR(255) NOT NULL, doc_type VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_8C9F3610ACAC0426 (mime_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, date_id INT NOT NULL, room_id INT NOT NULL, slug VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B12D4A36B897366B (date_id), INDEX IDX_B12D4A3654177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, start_date_id_id INT NOT NULL, end_date_id_id INT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, week INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7D053A93B77BD925 (start_date_id_id), INDEX IDX_7D053A93CBFC2E55 (end_date_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_dish (menu_id INT NOT NULL, dish_id INT NOT NULL, INDEX IDX_5D327CF6CCD7E912 (menu_id), INDEX IDX_5D327CF6148EB0CB (dish_id), PRIMARY KEY(menu_id, dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mime (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_group (notification_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_AB74A13CEF1A9D84 (notification_id), INDEX IDX_AB74A13CFE54D947 (group_id), PRIMARY KEY(notification_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, supplier_id INT NOT NULL, date_id INT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F52993982ADD6D8C (supplier_id), INDEX IDX_F5299398B897366B (date_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_products (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_749C879CCFFE9AD6 (orders_id), INDEX IDX_749C879C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, mime_id INT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_16DB4F89ACAC0426 (mime_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, supply_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price INT NOT NULL, currency VARCHAR(255) NOT NULL, conditionning VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D34A04AD79FBD03E (supply_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_picture (product_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_C70254394584665A (product_id), INDEX IDX_C7025439EE45BDBF (picture_id), PRIMARY KEY(product_id, picture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_supplier (product_id INT NOT NULL, supplier_id INT NOT NULL, INDEX IDX_509A06E94584665A (product_id), INDEX IDX_509A06E92ADD6D8C (supplier_id), PRIMARY KEY(product_id, supplier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_room (product_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_FF77733A4584665A (product_id), INDEX IDX_FF77733A54177093 (room_id), PRIMARY KEY(product_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, comments LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_user (supplier_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_40CB20A02ADD6D8C (supplier_id), INDEX IDX_40CB20A0A76ED395 (user_id), PRIMARY KEY(supplier_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supply_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', roles JSON NOT NULL, password VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_UUID (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_infos (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, business VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, whats_app VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C087935A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cooking_sheet ADD CONSTRAINT FK_CBD6B5D236F1D7E9 FOREIGN KEY (cooking_categories_id) REFERENCES cooking_category (id)');
        $this->addSql('ALTER TABLE cooking_sheet_picture ADD CONSTRAINT FK_E44996A1E4E09B9 FOREIGN KEY (cooking_sheet_id) REFERENCES cooking_sheet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cooking_sheet_picture ADD CONSTRAINT FK_E44996AEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_picture ADD CONSTRAINT FK_866C551F148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_picture ADD CONSTRAINT FK_866C551FEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_cooking_sheet ADD CONSTRAINT FK_570F017C148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_cooking_sheet ADD CONSTRAINT FK_570F017C1E4E09B9 FOREIGN KEY (cooking_sheet_id) REFERENCES cooking_sheet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C74B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C74F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE email_user ADD CONSTRAINT FK_12A5F6CCA832C1C9 FOREIGN KEY (email_id) REFERENCES email (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_user ADD CONSTRAINT FK_12A5F6CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_file ADD CONSTRAINT FK_13A91695A832C1C9 FOREIGN KEY (email_id) REFERENCES email (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email_file ADD CONSTRAINT FK_13A9169593CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610ACAC0426 FOREIGN KEY (mime_id) REFERENCES mime (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3654177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93B77BD925 FOREIGN KEY (start_date_id_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93CBFC2E55 FOREIGN KEY (end_date_id_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE menu_dish ADD CONSTRAINT FK_5D327CF6CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_dish ADD CONSTRAINT FK_5D327CF6148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_group ADD CONSTRAINT FK_AB74A13CEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_group ADD CONSTRAINT FK_AB74A13CFE54D947 FOREIGN KEY (group_id) REFERENCES `Group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993982ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879CCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89ACAC0426 FOREIGN KEY (mime_id) REFERENCES mime (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD79FBD03E FOREIGN KEY (supply_type_id) REFERENCES supply_type (id)');
        $this->addSql('ALTER TABLE product_picture ADD CONSTRAINT FK_C70254394584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_picture ADD CONSTRAINT FK_C7025439EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_supplier ADD CONSTRAINT FK_509A06E94584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_supplier ADD CONSTRAINT FK_509A06E92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_room ADD CONSTRAINT FK_FF77733A4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_room ADD CONSTRAINT FK_FF77733A54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_user ADD CONSTRAINT FK_40CB20A02ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_user ADD CONSTRAINT FK_40CB20A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_infos ADD CONSTRAINT FK_C087935A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cooking_sheet DROP FOREIGN KEY FK_CBD6B5D236F1D7E9');
        $this->addSql('ALTER TABLE cooking_sheet_picture DROP FOREIGN KEY FK_E44996A1E4E09B9');
        $this->addSql('ALTER TABLE cooking_sheet_picture DROP FOREIGN KEY FK_E44996AEE45BDBF');
        $this->addSql('ALTER TABLE dish_picture DROP FOREIGN KEY FK_866C551F148EB0CB');
        $this->addSql('ALTER TABLE dish_picture DROP FOREIGN KEY FK_866C551FEE45BDBF');
        $this->addSql('ALTER TABLE dish_cooking_sheet DROP FOREIGN KEY FK_570F017C148EB0CB');
        $this->addSql('ALTER TABLE dish_cooking_sheet DROP FOREIGN KEY FK_570F017C1E4E09B9');
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C74B897366B');
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C74F624B39D');
        $this->addSql('ALTER TABLE email_user DROP FOREIGN KEY FK_12A5F6CCA832C1C9');
        $this->addSql('ALTER TABLE email_user DROP FOREIGN KEY FK_12A5F6CCA76ED395');
        $this->addSql('ALTER TABLE email_file DROP FOREIGN KEY FK_13A91695A832C1C9');
        $this->addSql('ALTER TABLE email_file DROP FOREIGN KEY FK_13A9169593CB796C');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610ACAC0426');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36B897366B');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3654177093');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93B77BD925');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93CBFC2E55');
        $this->addSql('ALTER TABLE menu_dish DROP FOREIGN KEY FK_5D327CF6CCD7E912');
        $this->addSql('ALTER TABLE menu_dish DROP FOREIGN KEY FK_5D327CF6148EB0CB');
        $this->addSql('ALTER TABLE notification_group DROP FOREIGN KEY FK_AB74A13CEF1A9D84');
        $this->addSql('ALTER TABLE notification_group DROP FOREIGN KEY FK_AB74A13CFE54D947');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993982ADD6D8C');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B897366B');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879CCFFE9AD6');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879C4584665A');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89ACAC0426');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD79FBD03E');
        $this->addSql('ALTER TABLE product_picture DROP FOREIGN KEY FK_C70254394584665A');
        $this->addSql('ALTER TABLE product_picture DROP FOREIGN KEY FK_C7025439EE45BDBF');
        $this->addSql('ALTER TABLE product_supplier DROP FOREIGN KEY FK_509A06E94584665A');
        $this->addSql('ALTER TABLE product_supplier DROP FOREIGN KEY FK_509A06E92ADD6D8C');
        $this->addSql('ALTER TABLE product_room DROP FOREIGN KEY FK_FF77733A4584665A');
        $this->addSql('ALTER TABLE product_room DROP FOREIGN KEY FK_FF77733A54177093');
        $this->addSql('ALTER TABLE supplier_user DROP FOREIGN KEY FK_40CB20A02ADD6D8C');
        $this->addSql('ALTER TABLE supplier_user DROP FOREIGN KEY FK_40CB20A0A76ED395');
        $this->addSql('ALTER TABLE user_infos DROP FOREIGN KEY FK_C087935A76ED395');
        $this->addSql('DROP TABLE `Group`');
        $this->addSql('DROP TABLE cooking_category');
        $this->addSql('DROP TABLE cooking_sheet');
        $this->addSql('DROP TABLE cooking_sheet_picture');
        $this->addSql('DROP TABLE date');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE dish_picture');
        $this->addSql('DROP TABLE dish_cooking_sheet');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE email_user');
        $this->addSql('DROP TABLE email_file');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_dish');
        $this->addSql('DROP TABLE mime');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_group');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE orders_products');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_picture');
        $this->addSql('DROP TABLE product_supplier');
        $this->addSql('DROP TABLE product_room');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE supplier_user');
        $this->addSql('DROP TABLE supply_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_infos');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
