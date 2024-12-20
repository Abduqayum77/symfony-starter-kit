<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219133717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create district, region, password, phone_number, profile, roles, user_roles tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE district (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_31C1548798260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_35C246D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_number (id INT AUTO_INCREMENT NOT NULL, profile_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, phone VARCHAR(30) NOT NULL, INDEX IDX_6B01BC5BCCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, avatar_id INT DEFAULT NULL, region_id INT DEFAULT NULL, district_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, licence_number VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, full_address VARCHAR(255) DEFAULT NULL, telegram VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, status INT NOT NULL, type INT NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8157AA0FA76ED395 (user_id), INDEX IDX_8157AA0FB03A8386 (created_by_id), INDEX IDX_8157AA0F896DBBDE (updated_by_id), INDEX IDX_8157AA0FC76F1F52 (deleted_by_id), INDEX IDX_8157AA0F86383B10 (avatar_id), INDEX IDX_8157AA0F98260155 (region_id), INDEX IDX_8157AA0FB08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles (user_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_54FCD59FA76ED395 (user_id), INDEX IDX_54FCD59F38C751C4 (roles_id), PRIMARY KEY(user_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C1548798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE password ADD CONSTRAINT FK_35C246D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE phone_number ADD CONSTRAINT FK_6B01BC5BCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F86383B10 FOREIGN KEY (avatar_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FB08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59F38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_object ADD profile_id INT DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL, ADD date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE INDEX IDX_14D43132CCFA12B8 ON media_object (profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132CCFA12B8');
        $this->addSql('ALTER TABLE district DROP FOREIGN KEY FK_31C1548798260155');
        $this->addSql('ALTER TABLE password DROP FOREIGN KEY FK_35C246D5A76ED395');
        $this->addSql('ALTER TABLE phone_number DROP FOREIGN KEY FK_6B01BC5BCCFA12B8');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FA76ED395');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FB03A8386');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0F896DBBDE');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FC76F1F52');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0F86383B10');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0F98260155');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FB08FA272');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59F38C751C4');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE password');
        $this->addSql('DROP TABLE phone_number');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP INDEX IDX_14D43132CCFA12B8 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP profile_id, DROP name, DROP date');
    }
}
