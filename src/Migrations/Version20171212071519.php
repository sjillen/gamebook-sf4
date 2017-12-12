<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171212071519 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE saga (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE story ADD saga_id INT DEFAULT NULL, DROP saga');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438B2CCEE2E FOREIGN KEY (saga_id) REFERENCES saga (id)');
        $this->addSql('CREATE INDEX IDX_EB560438B2CCEE2E ON story (saga_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438B2CCEE2E');
        $this->addSql('DROP TABLE saga');
        $this->addSql('DROP INDEX IDX_EB560438B2CCEE2E ON story');
        $this->addSql('ALTER TABLE story ADD saga VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP saga_id');
    }
}
