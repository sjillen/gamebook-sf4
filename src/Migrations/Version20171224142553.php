<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171224142553 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chapter_weapon (chapter_id INT NOT NULL, weapon_id INT NOT NULL, INDEX IDX_326D406D579F4768 (chapter_id), INDEX IDX_326D406D95B82273 (weapon_id), PRIMARY KEY(chapter_id, weapon_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter_weapon ADD CONSTRAINT FK_326D406D579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter_weapon ADD CONSTRAINT FK_326D406D95B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE chapter_weapon');
    }
}
