<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160512143237 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sight_photos (id INT AUTO_INCREMENT NOT NULL, sight_id INT NOT NULL, user_id INT NOT NULL, photo_name VARCHAR(255) NOT NULL, name VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_932B462DDF12CCF2 (photo_name), INDEX IDX_932B462D983D68AB (sight_id), INDEX IDX_932B462DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sight_photos ADD CONSTRAINT FK_932B462D983D68AB FOREIGN KEY (sight_id) REFERENCES sights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sight_photos ADD CONSTRAINT FK_932B462DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sight_photos');
    }
}
