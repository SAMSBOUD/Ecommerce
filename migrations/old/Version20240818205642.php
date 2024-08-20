<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240818205642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blocage_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, blocked_until DATETIME NOT NULL, ip_address VARCHAR(45) NOT NULL, INDEX IDX_BC8DCF73A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_validation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code VARCHAR(6) NOT NULL, expires_at DATETIME NOT NULL, attempts INT NOT NULL, INDEX IDX_3E02A688A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blocage_user ADD CONSTRAINT FK_BC8DCF73A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE code_validation ADD CONSTRAINT FK_3E02A688A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blocage_user DROP FOREIGN KEY FK_BC8DCF73A76ED395');
        $this->addSql('ALTER TABLE code_validation DROP FOREIGN KEY FK_3E02A688A76ED395');
        $this->addSql('DROP TABLE blocage_user');
        $this->addSql('DROP TABLE code_validation');
    }
}
