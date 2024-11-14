<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113200849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD name_en VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE colection ADD title_en VARCHAR(255) NOT NULL, ADD description_en VARCHAR(255) NOT NULL, ADD button_text_en VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE page ADD title_en VARCHAR(255) NOT NULL, ADD content_en LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE sliders ADD title_en VARCHAR(255) NOT NULL, ADD description_en VARCHAR(255) NOT NULL, ADD button_text_en VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP name_en');
        $this->addSql('ALTER TABLE colection DROP title_en, DROP description_en, DROP button_text_en');
        $this->addSql('ALTER TABLE page DROP title_en, DROP content_en');
        $this->addSql('ALTER TABLE sliders DROP title_en, DROP description_en, DROP button_text_en');
    }
}
