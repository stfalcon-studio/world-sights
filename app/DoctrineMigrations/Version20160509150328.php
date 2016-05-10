<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160509150328 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sight_visits (id INT AUTO_INCREMENT NOT NULL, sight_id INT NOT NULL, user_id INT NOT NULL, date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DF159F1E983D68AB (sight_id), INDEX IDX_DF159F1EA76ED395 (user_id), UNIQUE INDEX UNIQ_DF159F1E983D68ABA76ED395 (sight_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sight_visits ADD CONSTRAINT FK_DF159F1E983D68AB FOREIGN KEY (sight_id) REFERENCES sights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sight_visits ADD CONSTRAINT FK_DF159F1EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sight_visits');
    }
}
