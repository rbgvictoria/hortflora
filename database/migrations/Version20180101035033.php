<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180101035033 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE flora.taxa ADD rank_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flora.taxa ADD CONSTRAINT FK_3A5A64927616678F FOREIGN KEY (rank_id) REFERENCES vocab.taxon_rank_vocab (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3A5A64927616678F ON flora.taxa (rank_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE flora.taxa DROP CONSTRAINT FK_3A5A64927616678F');
        $this->addSql('DROP INDEX IDX_3A5A64927616678F');
        $this->addSql('ALTER TABLE flora.taxa DROP rank_id');
    }
}
