<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206110634 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE npc (id INT AUTO_INCREMENT NOT NULL, story_id INT DEFAULT NULL, skill_affect_id INT DEFAULT NULL, energy INT NOT NULL, name VARCHAR(255) NOT NULL, life INT NOT NULL, INDEX IDX_468C762CAA5D4036 (story_id), INDEX IDX_468C762CFBF4D26A (skill_affect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE npc ADD CONSTRAINT FK_468C762CAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('ALTER TABLE npc ADD CONSTRAINT FK_468C762CFBF4D26A FOREIGN KEY (skill_affect_id) REFERENCES skill (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE npc');
    }
}
