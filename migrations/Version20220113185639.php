<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113185639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guild_user (guild_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(guild_id, user_id))');
        $this->addSql('CREATE INDEX IDX_6CC670E75F2131EF ON guild_user (guild_id)');
        $this->addSql('CREATE INDEX IDX_6CC670E7A76ED395 ON guild_user (user_id)');
        $this->addSql('ALTER TABLE guild_user ADD CONSTRAINT FK_6CC670E75F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guild_user ADD CONSTRAINT FK_6CC670E7A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE guild_user');
    }
}
