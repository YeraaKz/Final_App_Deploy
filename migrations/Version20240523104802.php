<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523104802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "comments_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "custom_item_attributes_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "item_attribute_values_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "items_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "items_collection_categories_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "items_collections_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "tags_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "comments" (id INT NOT NULL, user_id INT NOT NULL, item_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON "comments" (user_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A126F525E ON "comments" (item_id)');
        $this->addSql('CREATE TABLE "custom_item_attributes" (id INT NOT NULL, item_collection_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA066885BDE5FE26 ON "custom_item_attributes" (item_collection_id)');
        $this->addSql('CREATE TABLE "item_attribute_values" (id INT NOT NULL, attribute_id INT DEFAULT NULL, item_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_202461A3B6E62EFA ON "item_attribute_values" (attribute_id)');
        $this->addSql('CREATE INDEX IDX_202461A3126F525E ON "item_attribute_values" (item_id)');
        $this->addSql('CREATE TABLE "items" (id INT NOT NULL, collection_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E11EE94D514956FD ON "items" (collection_id)');
        $this->addSql('CREATE TABLE item_tag (item_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(item_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_E49CCCB1126F525E ON item_tag (item_id)');
        $this->addSql('CREATE INDEX IDX_E49CCCB1BAD26311 ON item_tag (tag_id)');
        $this->addSql('CREATE TABLE "items_collection_categories" (id INT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "items_collections" (id INT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B066EDF12469DE2 ON "items_collections" (category_id)');
        $this->addSql('CREATE INDEX IDX_6B066EDFA76ED395 ON "items_collections" (user_id)');
        $this->addSql('CREATE TABLE "tags" (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962A126F525E FOREIGN KEY (item_id) REFERENCES "items" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "custom_item_attributes" ADD CONSTRAINT FK_EA066885BDE5FE26 FOREIGN KEY (item_collection_id) REFERENCES "items_collections" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "item_attribute_values" ADD CONSTRAINT FK_202461A3B6E62EFA FOREIGN KEY (attribute_id) REFERENCES "custom_item_attributes" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "item_attribute_values" ADD CONSTRAINT FK_202461A3126F525E FOREIGN KEY (item_id) REFERENCES "items" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "items" ADD CONSTRAINT FK_E11EE94D514956FD FOREIGN KEY (collection_id) REFERENCES "items_collections" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_tag ADD CONSTRAINT FK_E49CCCB1126F525E FOREIGN KEY (item_id) REFERENCES "items" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_tag ADD CONSTRAINT FK_E49CCCB1BAD26311 FOREIGN KEY (tag_id) REFERENCES "tags" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "items_collections" ADD CONSTRAINT FK_6B066EDF12469DE2 FOREIGN KEY (category_id) REFERENCES "items_collection_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "items_collections" ADD CONSTRAINT FK_6B066EDFA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "comments_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "custom_item_attributes_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "item_attribute_values_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "items_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "items_collection_categories_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "items_collections_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "tags_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962A126F525E');
        $this->addSql('ALTER TABLE "custom_item_attributes" DROP CONSTRAINT FK_EA066885BDE5FE26');
        $this->addSql('ALTER TABLE "item_attribute_values" DROP CONSTRAINT FK_202461A3B6E62EFA');
        $this->addSql('ALTER TABLE "item_attribute_values" DROP CONSTRAINT FK_202461A3126F525E');
        $this->addSql('ALTER TABLE "items" DROP CONSTRAINT FK_E11EE94D514956FD');
        $this->addSql('ALTER TABLE item_tag DROP CONSTRAINT FK_E49CCCB1126F525E');
        $this->addSql('ALTER TABLE item_tag DROP CONSTRAINT FK_E49CCCB1BAD26311');
        $this->addSql('ALTER TABLE "items_collections" DROP CONSTRAINT FK_6B066EDF12469DE2');
        $this->addSql('ALTER TABLE "items_collections" DROP CONSTRAINT FK_6B066EDFA76ED395');
        $this->addSql('DROP TABLE "comments"');
        $this->addSql('DROP TABLE "custom_item_attributes"');
        $this->addSql('DROP TABLE "item_attribute_values"');
        $this->addSql('DROP TABLE "items"');
        $this->addSql('DROP TABLE item_tag');
        $this->addSql('DROP TABLE "items_collection_categories"');
        $this->addSql('DROP TABLE "items_collections"');
        $this->addSql('DROP TABLE "tags"');
    }
}
