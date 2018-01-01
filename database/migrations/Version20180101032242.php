<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180101032242 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE vocab.taxon_rank_vocab (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, modified_by_id INT DEFAULT NULL, text_before VARCHAR(16) DEFAULT NULL, text_after VARCHAR(16) DEFAULT NULL, full_name_separator VARCHAR(4) DEFAULT NULL, is_enforced BOOLEAN DEFAULT NULL, is_in_full_name BOOLEAN DEFAULT NULL, rank_id SMALLINT DEFAULT NULL, name VARCHAR(40) NOT NULL, uri VARCHAR(128) NOT NULL, label VARCHAR(64) NOT NULL, description TEXT DEFAULT NULL, guid UUID DEFAULT NULL, version SMALLINT NOT NULL, timestamp_created TIMESTAMP(0) WITH TIME ZONE NOT NULL, timestamp_modified TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2B61702B6FCFB2 ON vocab.taxon_rank_vocab (guid)');
        $this->addSql('CREATE INDEX IDX_D2B6170727ACA70 ON vocab.taxon_rank_vocab (parent_id)');
        $this->addSql('CREATE INDEX IDX_D2B6170B03A8386 ON vocab.taxon_rank_vocab (created_by_id)');
        $this->addSql('CREATE INDEX IDX_D2B617099049ECE ON vocab.taxon_rank_vocab (modified_by_id)');
        $this->addSql('CREATE INDEX IDX_D2B61705E237E06 ON vocab.taxon_rank_vocab (name)');
        $this->addSql('CREATE INDEX IDX_D2B6170841CB121 ON vocab.taxon_rank_vocab (uri)');
        $this->addSql('CREATE INDEX IDX_D2B6170EA750E8 ON vocab.taxon_rank_vocab (label)');
        $this->addSql('COMMENT ON COLUMN vocab.taxon_rank_vocab.guid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE vocab.taxon_rank_vocab ADD CONSTRAINT FK_D2B6170727ACA70 FOREIGN KEY (parent_id) REFERENCES vocab.taxon_rank_vocab (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vocab.taxon_rank_vocab ADD CONSTRAINT FK_D2B6170B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vocab.taxon_rank_vocab ADD CONSTRAINT FK_D2B617099049ECE FOREIGN KEY (modified_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vocab.taxon_rank_vocab DROP CONSTRAINT FK_D2B6170727ACA70');
        $this->addSql('DROP TABLE vocab.taxon_rank_vocab');
    }
}
