<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171218062712 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE47795B82273');
        $this->addSql('DROP INDEX UNIQ_5E3DE47795B82273 ON skill');
        $this->addSql('ALTER TABLE skill ADD weapon VARCHAR(255) NOT NULL, CHANGE weapon_id weapon_mastered_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE4779E739BDD FOREIGN KEY (weapon_mastered_id) REFERENCES weapon (id)');
        $this->addSql('CREATE INDEX IDX_5E3DE4779E739BDD ON skill (weapon_mastered_id)');
        $this->addSql('ALTER TABLE hero ADD weaponskill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E8684FA607B FOREIGN KEY (weaponskill_id) REFERENCES skill (id)');
        $this->addSql('CREATE INDEX IDX_51CE6E8684FA607B ON hero (weaponskill_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E8684FA607B');
        $this->addSql('DROP INDEX IDX_51CE6E8684FA607B ON hero');
        $this->addSql('ALTER TABLE hero DROP weaponskill_id');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE4779E739BDD');
        $this->addSql('DROP INDEX IDX_5E3DE4779E739BDD ON skill');
        $this->addSql('ALTER TABLE skill DROP weapon, CHANGE weapon_mastered_id weapon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE47795B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E3DE47795B82273 ON skill (weapon_id)');
    }
}
