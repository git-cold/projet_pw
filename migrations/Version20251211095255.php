<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211095255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, formation VARCHAR(255) NOT NULL, tuteur_id INTEGER DEFAULT NULL, CONSTRAINT FK_717E22E386EC68D8 FOREIGN KEY (tuteur_id) REFERENCES tuteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_717E22E386EC68D8 ON etudiant (tuteur_id)');
        $this->addSql('CREATE TABLE tuteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE visite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL, commentaire VARCHAR(255) NOT NULL, compte_rendu CLOB DEFAULT NULL, statut VARCHAR(255) NOT NULL, tuteur_id INTEGER DEFAULT NULL, etudiant_id INTEGER DEFAULT NULL, CONSTRAINT FK_B09C8CBB86EC68D8 FOREIGN KEY (tuteur_id) REFERENCES tuteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B09C8CBBDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B09C8CBB86EC68D8 ON visite (tuteur_id)');
        $this->addSql('CREATE INDEX IDX_B09C8CBBDDEAB1A3 ON visite (etudiant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE tuteur');
        $this->addSql('DROP TABLE visite');
    }
}
