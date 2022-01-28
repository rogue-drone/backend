<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128041251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE doctrine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guild_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_replacement_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE doctrine (id INT NOT NULL, name VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE doctrine_ship (doctrine_id INT NOT NULL, ship_id INT NOT NULL, PRIMARY KEY(doctrine_id, ship_id))');
        $this->addSql('CREATE INDEX IDX_5E1A4558164640F ON doctrine_ship (doctrine_id)');
        $this->addSql('CREATE INDEX IDX_5E1A4558C256317D ON doctrine_ship (ship_id)');
        $this->addSql('CREATE TABLE guild (id INT NOT NULL, name VARCHAR(255) NOT NULL, discord_id VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guilds_administrators (guild_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(guild_id, user_id))');
        $this->addSql('CREATE INDEX IDX_1BAC4E4B5F2131EF ON guilds_administrators (guild_id)');
        $this->addSql('CREATE INDEX IDX_1BAC4E4BA76ED395 ON guilds_administrators (user_id)');
        $this->addSql('CREATE TABLE guilds_users (guild_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(guild_id, user_id))');
        $this->addSql('CREATE INDEX IDX_99976C725F2131EF ON guilds_users (guild_id)');
        $this->addSql('CREATE INDEX IDX_99976C72A76ED395 ON guilds_users (user_id)');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, fleet_commander_id INT NOT NULL, name VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1981A66DCF5592C8 ON operation (fleet_commander_id)');
        $this->addSql('CREATE TABLE ship (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ship_replacement_request (id INT NOT NULL, player_id INT NOT NULL, operation_id INT NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4253F9299E6F5DF ON ship_replacement_request (player_id)');
        $this->addSql('CREATE INDEX IDX_4253F9244AC3583 ON ship_replacement_request (operation_id)');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, discord_id VARCHAR(255) NOT NULL, current_access_token TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON "users" (username)');
        $this->addSql('ALTER TABLE doctrine_ship ADD CONSTRAINT FK_5E1A4558164640F FOREIGN KEY (doctrine_id) REFERENCES doctrine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctrine_ship ADD CONSTRAINT FK_5E1A4558C256317D FOREIGN KEY (ship_id) REFERENCES ship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guilds_administrators ADD CONSTRAINT FK_1BAC4E4B5F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guilds_administrators ADD CONSTRAINT FK_1BAC4E4BA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guilds_users ADD CONSTRAINT FK_99976C725F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guilds_users ADD CONSTRAINT FK_99976C72A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DCF5592C8 FOREIGN KEY (fleet_commander_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_replacement_request ADD CONSTRAINT FK_4253F9299E6F5DF FOREIGN KEY (player_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_replacement_request ADD CONSTRAINT FK_4253F9244AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctrine_ship DROP CONSTRAINT FK_5E1A4558164640F');
        $this->addSql('ALTER TABLE guilds_administrators DROP CONSTRAINT FK_1BAC4E4B5F2131EF');
        $this->addSql('ALTER TABLE guilds_users DROP CONSTRAINT FK_99976C725F2131EF');
        $this->addSql('ALTER TABLE ship_replacement_request DROP CONSTRAINT FK_4253F9244AC3583');
        $this->addSql('ALTER TABLE doctrine_ship DROP CONSTRAINT FK_5E1A4558C256317D');
        $this->addSql('ALTER TABLE guilds_administrators DROP CONSTRAINT FK_1BAC4E4BA76ED395');
        $this->addSql('ALTER TABLE guilds_users DROP CONSTRAINT FK_99976C72A76ED395');
        $this->addSql('ALTER TABLE operation DROP CONSTRAINT FK_1981A66DCF5592C8');
        $this->addSql('ALTER TABLE ship_replacement_request DROP CONSTRAINT FK_4253F9299E6F5DF');
        $this->addSql('DROP SEQUENCE doctrine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guild_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_replacement_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('DROP TABLE doctrine');
        $this->addSql('DROP TABLE doctrine_ship');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP TABLE guilds_administrators');
        $this->addSql('DROP TABLE guilds_users');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE ship');
        $this->addSql('DROP TABLE ship_replacement_request');
        $this->addSql('DROP TABLE "users"');
    }
}
