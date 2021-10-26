<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915100542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0ADDEAB1A3 ON rendez_vous (etudiant_id)');
        $this->addSql('ALTER TABLE tdocument CHANGE file file VARCHAR(20000) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ADDEAB1A3');
        $this->addSql('DROP INDEX IDX_65E8AA0ADDEAB1A3 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous DROP etudiant_id');
        $this->addSql('ALTER TABLE tdocument CHANGE file file MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
