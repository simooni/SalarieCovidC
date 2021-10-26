<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921085403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_create_id INT NOT NULL, text VARCHAR(3000) DEFAULT NULL, INDEX IDX_B6BD307FEEFE5067 (user_create_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, user_create_id INT DEFAULT NULL, etudiant_id INT DEFAULT NULL, date_rv DATETIME DEFAULT NULL, INDEX IDX_65E8AA0AEEFE5067 (user_create_id), INDEX IDX_65E8AA0ADDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tdocument (id INT AUTO_INCREMENT NOT NULL, user_create_id INT NOT NULL, file VARCHAR(20000) NOT NULL, type_document VARCHAR(255) DEFAULT NULL, supprimer VARCHAR(255) DEFAULT NULL, INDEX IDX_F60DD368EEFE5067 (user_create_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, user VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6498D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEEFE5067 FOREIGN KEY (user_create_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AEEFE5067 FOREIGN KEY (user_create_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE tdocument ADD CONSTRAINT FK_F60DD368EEFE5067 FOREIGN KEY (user_create_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FEEFE5067');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AEEFE5067');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ADDEAB1A3');
        $this->addSql('ALTER TABLE tdocument DROP FOREIGN KEY FK_F60DD368EEFE5067');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE tdocument');
        $this->addSql('DROP TABLE `user`');
    }
}
