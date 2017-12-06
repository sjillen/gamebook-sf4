<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206132535 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE weapon ADD weapon_skill_id INT DEFAULT NULL, DROP weapon_skill');
        $this->addSql('ALTER TABLE weapon ADD CONSTRAINT FK_6933A7E62491738B FOREIGN KEY (weapon_skill_id) REFERENCES skill (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6933A7E62491738B ON weapon (weapon_skill_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE weapon DROP FOREIGN KEY FK_6933A7E62491738B');
        $this->addSql('DROP INDEX UNIQ_6933A7E62491738B ON weapon');
        $this->addSql('ALTER TABLE weapon ADD weapon_skill VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP weapon_skill_id');
    }
}
