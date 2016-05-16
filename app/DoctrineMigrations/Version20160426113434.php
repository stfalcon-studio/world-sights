<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160426113434 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_CA3C02A6989D9B62 ON sights (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_166E83CE989D9B62 ON sight_tours (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE0DF657989D9B62 ON sight_tickets (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_EE0DF657989D9B62 ON sight_tickets');
        $this->addSql('DROP INDEX UNIQ_166E83CE989D9B62 ON sight_tours');
        $this->addSql('DROP INDEX UNIQ_CA3C02A6989D9B62 ON sights');
    }
}
