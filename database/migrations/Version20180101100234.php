<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180101100234 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE flora.taxa DROP CONSTRAINT fk_3a5a6492e05bc1d2');
        $this->addSql('DROP INDEX flora.idx_3a5a6492e05bc1d2');
        $this->addSql('ALTER TABLE flora.taxa DROP taxon_tree_def_item_id');
        $this->addSql('ALTER TABLE flora.taxon_tree_def_items DROP CONSTRAINT fk_86f092a60272618');
        $this->addSql('DROP SEQUENCE flora.taxon_tree_def_items_id_seq CASCADE');
        $this->addSql('DROP TABLE flora.taxon_tree_def_items');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE flora.taxon_tree_def_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE flora.taxon_tree_def_items (id SERIAL NOT NULL, parent_item_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, modified_by_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, text_before VARCHAR(16) DEFAULT NULL, text_after VARCHAR(16) DEFAULT NULL, full_name_separator VARCHAR(4) DEFAULT NULL, is_enforced BOOLEAN DEFAULT NULL, is_in_full_name BOOLEAN DEFAULT NULL, rank_id SMALLINT DEFAULT NULL, timestamp_created TIMESTAMP(0) WITH TIME ZONE NOT NULL, timestamp_modified TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, guid UUID DEFAULT NULL, version SMALLINT NOT NULL, uri VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_86f092a2b6fcfb2 ON flora.taxon_tree_def_items (guid)');
        $this->addSql('CREATE INDEX idx_86f092a60272618 ON flora.taxon_tree_def_items (parent_item_id)');
        $this->addSql('CREATE INDEX idx_86f092a99049ece ON flora.taxon_tree_def_items (modified_by_id)');
        $this->addSql('CREATE INDEX idx_86f092ab03a8386 ON flora.taxon_tree_def_items (created_by_id)');
        $this->addSql('CREATE INDEX idx_86f092a7616678f ON flora.taxon_tree_def_items (rank_id)');
        $this->addSql('CREATE INDEX idx_86f092a5e237e06 ON flora.taxon_tree_def_items (name)');
        $this->addSql('COMMENT ON COLUMN flora.taxon_tree_def_items.guid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE flora.taxon_tree_def_items ADD CONSTRAINT fk_86f092a60272618 FOREIGN KEY (parent_item_id) REFERENCES flora.taxon_tree_def_items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flora.taxon_tree_def_items ADD CONSTRAINT fk_86f092ab03a8386 FOREIGN KEY (created_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flora.taxon_tree_def_items ADD CONSTRAINT fk_86f092a99049ece FOREIGN KEY (modified_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flora.taxa ADD taxon_tree_def_item_id INT NOT NULL');
        $this->addSql('ALTER TABLE flora.taxa ADD CONSTRAINT fk_3a5a6492e05bc1d2 FOREIGN KEY (taxon_tree_def_item_id) REFERENCES flora.taxon_tree_def_items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3a5a6492e05bc1d2 ON flora.taxa (taxon_tree_def_item_id)');
    }
}
