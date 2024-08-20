<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805225502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, fk_category_id INT NOT NULL, libelle VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C52F9587BB031D6 (fk_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9587BB031D6 FOREIGN KEY (fk_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD fk_brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD76DD93D6 FOREIGN KEY (fk_brand_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD76DD93D6 ON product (fk_brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD76DD93D6');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F9587BB031D6');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP INDEX IDX_D34A04AD76DD93D6 ON product');
        $this->addSql('ALTER TABLE product DROP fk_brand_id');
    }
}
