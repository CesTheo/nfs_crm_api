<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221225173906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC789D86650F');
        $this->addSql('ALTER TABLE history_lead DROP FOREIGN KEY FK_23A411B79D86650F');
        $this->addSql('ALTER TABLE history_lead DROP FOREIGN KEY FK_23A411B71577CB43');
        $this->addSql('ALTER TABLE history_paiment DROP FOREIGN KEY FK_29AF3F655C47468');
        $this->addSql('DROP TABLE fiche');
        $this->addSql('DROP TABLE history_lead');
        $this->addSql('DROP TABLE history_paiment');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD phone VARCHAR(255) NOT NULL, ADD role VARCHAR(255) NOT NULL, DROP roles, DROP tel, DROP created_at, DROP updated_at, CHANGE email email VARCHAR(255) NOT NULL, CHANGE is_verify verify TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiche (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, categorie VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4C13CC789D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE history_lead (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, user_id_target_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, text LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_23A411B79D86650F (user_id_id), INDEX IDX_23A411B71577CB43 (user_id_target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE history_paiment (id INT AUTO_INCREMENT NOT NULL, fiche_id_id INT DEFAULT NULL, is_paid TINYINT(1) NOT NULL, execute_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_29AF3F655C47468 (fiche_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC789D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_lead ADD CONSTRAINT FK_23A411B79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_lead ADD CONSTRAINT FK_23A411B71577CB43 FOREIGN KEY (user_id_target_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_paiment ADD CONSTRAINT FK_29AF3F655C47468 FOREIGN KEY (fiche_id_id) REFERENCES fiche (id)');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD tel VARCHAR(20) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP phone, DROP role, CHANGE email email VARCHAR(180) NOT NULL, CHANGE verify is_verify TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
