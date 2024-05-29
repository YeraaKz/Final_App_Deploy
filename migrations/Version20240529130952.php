<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529130952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_item_attributes DROP CONSTRAINT FK_EA066885BDE5FE26');
        $this->addSql('ALTER TABLE custom_item_attributes ADD CONSTRAINT FK_EA066885BDE5FE26 FOREIGN KEY (item_collection_id) REFERENCES "items_collections" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items DROP CONSTRAINT FK_E11EE94D514956FD');
        $this->addSql('ALTER TABLE items ALTER collection_id SET NOT NULL');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D514956FD FOREIGN KEY (collection_id) REFERENCES "items_collections" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items_collections ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "items_collections" DROP updated_at');
        $this->addSql('ALTER TABLE "items" DROP CONSTRAINT fk_e11ee94d514956fd');
        $this->addSql('ALTER TABLE "items" ALTER collection_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "items" ADD CONSTRAINT fk_e11ee94d514956fd FOREIGN KEY (collection_id) REFERENCES items_collections (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "custom_item_attributes" DROP CONSTRAINT fk_ea066885bde5fe26');
        $this->addSql('ALTER TABLE "custom_item_attributes" ADD CONSTRAINT fk_ea066885bde5fe26 FOREIGN KEY (item_collection_id) REFERENCES items_collections (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
