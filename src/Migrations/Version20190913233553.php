<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190913233553 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE composant (id INT AUTO_INCREMENT NOT NULL, liste_id INT DEFAULT NULL, tableau_id INT DEFAULT NULL, last_editor_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, contenu VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_EC8486C9E85441D8 (liste_id), INDEX IDX_EC8486C9B062D5BC (tableau_id), INDEX IDX_EC8486C97E5A734A (last_editor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composant_type_tab (composant_id INT NOT NULL, type_tab_id INT NOT NULL, INDEX IDX_C3DBCCDB7F3310E7 (composant_id), INDEX IDX_C3DBCCDBAEDBF81B (type_tab_id), PRIMARY KEY(composant_id, type_tab_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE droit_accee (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_compo (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, droit_accee_id INT DEFAULT NULL, type_tab_id INT DEFAULT NULL, INDEX IDX_D6339437A0905086 (poste_id), INDEX IDX_D63394375121283C (droit_accee_id), INDEX IDX_D6339437AEDBF81B (type_tab_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_compo_composant (liste_compo_id INT NOT NULL, composant_id INT NOT NULL, INDEX IDX_7F1FCF46ED9818FB (liste_compo_id), INDEX IDX_7F1FCF467F3310E7 (composant_id), PRIMARY KEY(liste_compo_id, composant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableat (id INT AUTO_INCREMENT NOT NULL, date_creation DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau (id INT AUTO_INCREMENT NOT NULL, type_tab_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C6744DB1AEDBF81B (type_tab_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_tab (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, presnom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, derniere_cnx DATETIME DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_8D93D649A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE composant ADD CONSTRAINT FK_EC8486C9E85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id)');
        $this->addSql('ALTER TABLE composant ADD CONSTRAINT FK_EC8486C9B062D5BC FOREIGN KEY (tableau_id) REFERENCES tableau (id)');
        $this->addSql('ALTER TABLE composant ADD CONSTRAINT FK_EC8486C97E5A734A FOREIGN KEY (last_editor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE composant_type_tab ADD CONSTRAINT FK_C3DBCCDB7F3310E7 FOREIGN KEY (composant_id) REFERENCES composant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composant_type_tab ADD CONSTRAINT FK_C3DBCCDBAEDBF81B FOREIGN KEY (type_tab_id) REFERENCES type_tab (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_compo ADD CONSTRAINT FK_D6339437A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE liste_compo ADD CONSTRAINT FK_D63394375121283C FOREIGN KEY (droit_accee_id) REFERENCES droit_accee (id)');
        $this->addSql('ALTER TABLE liste_compo ADD CONSTRAINT FK_D6339437AEDBF81B FOREIGN KEY (type_tab_id) REFERENCES type_tab (id)');
        $this->addSql('ALTER TABLE liste_compo_composant ADD CONSTRAINT FK_7F1FCF46ED9818FB FOREIGN KEY (liste_compo_id) REFERENCES liste_compo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_compo_composant ADD CONSTRAINT FK_7F1FCF467F3310E7 FOREIGN KEY (composant_id) REFERENCES composant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau ADD CONSTRAINT FK_C6744DB1AEDBF81B FOREIGN KEY (type_tab_id) REFERENCES type_tab (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE composant_type_tab DROP FOREIGN KEY FK_C3DBCCDB7F3310E7');
        $this->addSql('ALTER TABLE liste_compo_composant DROP FOREIGN KEY FK_7F1FCF467F3310E7');
        $this->addSql('ALTER TABLE liste_compo DROP FOREIGN KEY FK_D63394375121283C');
        $this->addSql('ALTER TABLE composant DROP FOREIGN KEY FK_EC8486C9E85441D8');
        $this->addSql('ALTER TABLE liste_compo_composant DROP FOREIGN KEY FK_7F1FCF46ED9818FB');
        $this->addSql('ALTER TABLE liste_compo DROP FOREIGN KEY FK_D6339437A0905086');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A0905086');
        $this->addSql('ALTER TABLE composant DROP FOREIGN KEY FK_EC8486C9B062D5BC');
        $this->addSql('ALTER TABLE composant_type_tab DROP FOREIGN KEY FK_C3DBCCDBAEDBF81B');
        $this->addSql('ALTER TABLE liste_compo DROP FOREIGN KEY FK_D6339437AEDBF81B');
        $this->addSql('ALTER TABLE tableau DROP FOREIGN KEY FK_C6744DB1AEDBF81B');
        $this->addSql('ALTER TABLE composant DROP FOREIGN KEY FK_EC8486C97E5A734A');
        $this->addSql('DROP TABLE composant');
        $this->addSql('DROP TABLE composant_type_tab');
        $this->addSql('DROP TABLE droit_accee');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE liste_compo');
        $this->addSql('DROP TABLE liste_compo_composant');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE tableat');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('DROP TABLE type_tab');
        $this->addSql('DROP TABLE user');
    }
}
