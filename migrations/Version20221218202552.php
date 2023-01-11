<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221218202552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiche (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, categorie VARCHAR(30) NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4C13CC789D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history_lead (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, user_id_target_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_23A411B79D86650F (user_id_id), INDEX IDX_23A411B71577CB43 (user_id_target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history_paiment (id INT AUTO_INCREMENT NOT NULL, fiche_id_id INT DEFAULT NULL, is_paid TINYINT(1) NOT NULL, execute_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_29AF3F655C47468 (fiche_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, tel VARCHAR(20) NOT NULL, is_verify TINYINT(1) NOT NULL, update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', society VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC789D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_lead ADD CONSTRAINT FK_23A411B79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_lead ADD CONSTRAINT FK_23A411B71577CB43 FOREIGN KEY (user_id_target_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_paiment ADD CONSTRAINT FK_29AF3F655C47468 FOREIGN KEY (fiche_id_id) REFERENCES fiche (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC789D86650F');
        $this->addSql('ALTER TABLE history_lead DROP FOREIGN KEY FK_23A411B79D86650F');
        $this->addSql('ALTER TABLE history_lead DROP FOREIGN KEY FK_23A411B71577CB43');
        $this->addSql('ALTER TABLE history_paiment DROP FOREIGN KEY FK_29AF3F655C47468');
        $this->addSql('DROP TABLE fiche');
        $this->addSql('DROP TABLE history_lead');
        $this->addSql('DROP TABLE history_paiment');
        $this->addSql('DROP TABLE user');
    }
}
