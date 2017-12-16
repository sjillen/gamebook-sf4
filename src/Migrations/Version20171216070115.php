<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171216070115 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE weapon ADD starter TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE chapter CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE consumable_item ADD starter TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE special_item ADD starter TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter CHANGE type type INT NOT NULL');
        $this->addSql('ALTER TABLE consumable_item DROP starter');
        $this->addSql('ALTER TABLE special_item DROP starter');
        $this->addSql('ALTER TABLE weapon DROP starter');
    }
}
