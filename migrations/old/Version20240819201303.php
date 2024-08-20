<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819201303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blocage_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, blocked_until DATETIME NOT NULL, ip_address VARCHAR(45) NOT NULL, INDEX IDX_BC8DCF73A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, fk_category_id INT NOT NULL, libelle VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C52F9587BB031D6 (fk_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_validation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code VARCHAR(6) NOT NULL, expires_at DATETIME NOT NULL, attempts INT NOT NULL, INDEX IDX_3E02A688A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_name VARCHAR(255) NOT NULL, billing_address LONGTEXT DEFAULT NULL, shipping_address LONGTEXT DEFAULT NULL, quantity INT NOT NULL, order_cost_ht INT DEFAULT NULL, taxe INT DEFAULT NULL, order_cost_ttc INT NOT NULL, is_paid TINYINT(1) DEFAULT NULL, status VARCHAR(255) NOT NULL, carrier_price INT NOT NULL, carrier_name VARCHAR(255) NOT NULL, carrier_id INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', stripe_client_secret VARCHAR(255) DEFAULT NULL, paypal_client_secret VARCHAR(255) DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, my_order_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_description VARCHAR(255) NOT NULL, product_solde_price INT NOT NULL, product_regular_price INT NOT NULL, quantity INT NOT NULL, taxe INT DEFAULT NULL, subtotal INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_845CA2C1BFCDF877 (my_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, more_description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, test_public_api_key LONGTEXT DEFAULT NULL, test_private_api_key LONGTEXT DEFAULT NULL, prod_public_api_key LONGTEXT DEFAULT NULL, prod_private_api_key LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', test_base_url LONGTEXT DEFAULT NULL, prod_base_url LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blocage_user ADD CONSTRAINT FK_BC8DCF73A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9587BB031D6 FOREIGN KEY (fk_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE code_validation ADD CONSTRAINT FK_3E02A688A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BFCDF877 FOREIGN KEY (my_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD fk_brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD76DD93D6 FOREIGN KEY (fk_brand_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD76DD93D6 ON product (fk_brand_id)');
        $this->addSql('ALTER TABLE user ADD is_double_factor TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD76DD93D6');
        $this->addSql('ALTER TABLE blocage_user DROP FOREIGN KEY FK_BC8DCF73A76ED395');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F9587BB031D6');
        $this->addSql('ALTER TABLE code_validation DROP FOREIGN KEY FK_3E02A688A76ED395');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1BFCDF877');
        $this->addSql('DROP TABLE blocage_user');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE code_validation');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP INDEX IDX_D34A04AD76DD93D6 ON product');
        $this->addSql('ALTER TABLE product DROP fk_brand_id');
        $this->addSql('ALTER TABLE `user` DROP is_double_factor');
    }
}
