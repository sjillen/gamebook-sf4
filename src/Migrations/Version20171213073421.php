<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213073421 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE skill CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE weapon CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE consumable_item CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE special_item CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE npc CHANGE description description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consumable_item CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE npc CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE skill CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE special_item CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE weapon CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
