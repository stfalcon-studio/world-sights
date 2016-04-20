<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160420163731 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sights (id INT AUTO_INCREMENT NOT NULL, sight_type_id INT NOT NULL, locality_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, tags LONGTEXT DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, slug VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CA3C02A67F07E730 (sight_type_id), INDEX IDX_CA3C02A688823A92 (locality_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sight_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sight_tours (id INT AUTO_INCREMENT NOT NULL, sight_id INT NOT NULL, name VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, company_link VARCHAR(255) DEFAULT NULL, tour_link VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, slug VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_166E83CE983D68AB (sight_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localities (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_41E780E9F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sight_tickets (id INT AUTO_INCREMENT NOT NULL, sight_id INT NOT NULL, from_id INT NOT NULL, to_id INT NOT NULL, type VARCHAR(255) NOT NULL, link_buy VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EE0DF657983D68AB (sight_id), INDEX IDX_EE0DF65778CED90B (from_id), INDEX IDX_EE0DF65730354A65 (to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sights ADD CONSTRAINT FK_CA3C02A67F07E730 FOREIGN KEY (sight_type_id) REFERENCES sight_types (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sights ADD CONSTRAINT FK_CA3C02A688823A92 FOREIGN KEY (locality_id) REFERENCES localities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sight_tours ADD CONSTRAINT FK_166E83CE983D68AB FOREIGN KEY (sight_id) REFERENCES sights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE localities ADD CONSTRAINT FK_41E780E9F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sight_tickets ADD CONSTRAINT FK_EE0DF657983D68AB FOREIGN KEY (sight_id) REFERENCES sights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sight_tickets ADD CONSTRAINT FK_EE0DF65778CED90B FOREIGN KEY (from_id) REFERENCES localities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sight_tickets ADD CONSTRAINT FK_EE0DF65730354A65 FOREIGN KEY (to_id) REFERENCES localities (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sight_tours DROP FOREIGN KEY FK_166E83CE983D68AB');
        $this->addSql('ALTER TABLE sight_tickets DROP FOREIGN KEY FK_EE0DF657983D68AB');
        $this->addSql('ALTER TABLE sights DROP FOREIGN KEY FK_CA3C02A67F07E730');
        $this->addSql('ALTER TABLE localities DROP FOREIGN KEY FK_41E780E9F92F3E70');
        $this->addSql('ALTER TABLE sights DROP FOREIGN KEY FK_CA3C02A688823A92');
        $this->addSql('ALTER TABLE sight_tickets DROP FOREIGN KEY FK_EE0DF65778CED90B');
        $this->addSql('ALTER TABLE sight_tickets DROP FOREIGN KEY FK_EE0DF65730354A65');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE sights');
        $this->addSql('DROP TABLE sight_types');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE sight_tours');
        $this->addSql('DROP TABLE localities');
        $this->addSql('DROP TABLE sight_tickets');
    }
}
