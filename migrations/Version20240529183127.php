<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529183127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_attribute_values DROP CONSTRAINT FK_202461A3B6E62EFA');
        $this->addSql('ALTER TABLE item_attribute_values ADD CONSTRAINT FK_202461A3B6E62EFA FOREIGN KEY (attribute_id) REFERENCES "custom_item_attributes" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "item_attribute_values" DROP CONSTRAINT fk_202461a3b6e62efa');
        $this->addSql('ALTER TABLE "item_attribute_values" ADD CONSTRAINT fk_202461a3b6e62efa FOREIGN KEY (attribute_id) REFERENCES custom_item_attributes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
