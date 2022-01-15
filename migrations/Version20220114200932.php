<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114200932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE doctrine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ship_replacement_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE doctrine (id INT NOT NULL, name VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE doctrine_ship (doctrine_id INT NOT NULL, ship_id INT NOT NULL, PRIMARY KEY(doctrine_id, ship_id))');
        $this->addSql('CREATE INDEX IDX_5E1A4558164640F ON doctrine_ship (doctrine_id)');
        $this->addSql('CREATE INDEX IDX_5E1A4558C256317D ON doctrine_ship (ship_id)');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, fleet_commander_id INT NOT NULL, name VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1981A66DCF5592C8 ON operation (fleet_commander_id)');
        $this->addSql('CREATE TABLE ship (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ship_replacement_request (id INT NOT NULL, player_id INT NOT NULL, operation_id INT NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4253F9299E6F5DF ON ship_replacement_request (player_id)');
        $this->addSql('CREATE INDEX IDX_4253F9244AC3583 ON ship_replacement_request (operation_id)');
        $this->addSql('ALTER TABLE doctrine_ship ADD CONSTRAINT FK_5E1A4558164640F FOREIGN KEY (doctrine_id) REFERENCES doctrine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctrine_ship ADD CONSTRAINT FK_5E1A4558C256317D FOREIGN KEY (ship_id) REFERENCES ship (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DCF5592C8 FOREIGN KEY (fleet_commander_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_replacement_request ADD CONSTRAINT FK_4253F9299E6F5DF FOREIGN KEY (player_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ship_replacement_request ADD CONSTRAINT FK_4253F9244AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE doctrine_ship DROP CONSTRAINT FK_5E1A4558164640F');
        $this->addSql('ALTER TABLE ship_replacement_request DROP CONSTRAINT FK_4253F9244AC3583');
        $this->addSql('ALTER TABLE doctrine_ship DROP CONSTRAINT FK_5E1A4558C256317D');
        $this->addSql('DROP SEQUENCE doctrine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ship_replacement_request_id_seq CASCADE');
        $this->addSql('DROP TABLE doctrine');
        $this->addSql('DROP TABLE doctrine_ship');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE ship');
        $this->addSql('DROP TABLE ship_replacement_request');
    }
}
