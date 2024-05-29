<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529131448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3126F525E');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3126F525E FOREIGN KEY (item_id) REFERENCES "items" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT fk_ac6340b3126f525e');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT fk_ac6340b3126f525e FOREIGN KEY (item_id) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
