<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180110021429 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE story ADD user_id INT DEFAULT NULL, ADD is_published TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EB560438A76ED395 ON story (user_id)');
        $this->addSql('ALTER TABLE hero ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E86A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_51CE6E86A76ED395 ON hero (user_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E86A76ED395');
        $this->addSql('DROP INDEX IDX_51CE6E86A76ED395 ON hero');
        $this->addSql('ALTER TABLE hero DROP user_id');
        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438A76ED395');
        $this->addSql('DROP INDEX IDX_EB560438A76ED395 ON story');
        $this->addSql('ALTER TABLE story DROP user_id, DROP is_published');
    }
}
