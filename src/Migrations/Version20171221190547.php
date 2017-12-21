<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171221190547 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE backpack_item (id INT AUTO_INCREMENT NOT NULL, hero_id INT DEFAULT NULL, item_id INT DEFAULT NULL, stock INT NOT NULL, INDEX IDX_828E6E4745B0BCD (hero_id), INDEX IDX_828E6E47126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE backpack_item ADD CONSTRAINT FK_828E6E4745B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE backpack_item ADD CONSTRAINT FK_828E6E47126F525E FOREIGN KEY (item_id) REFERENCES consumable_item (id)');
        $this->addSql('DROP TABLE hero_consumable_item');
        $this->addSql('ALTER TABLE ruleset CHANGE bagpack_capacity backpack_capacity INT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hero_consumable_item (hero_id INT NOT NULL, consumable_item_id INT NOT NULL, INDEX IDX_1B26567545B0BCD (hero_id), INDEX IDX_1B26567593A55194 (consumable_item_id), PRIMARY KEY(hero_id, consumable_item_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hero_consumable_item ADD CONSTRAINT FK_1B26567545B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hero_consumable_item ADD CONSTRAINT FK_1B26567593A55194 FOREIGN KEY (consumable_item_id) REFERENCES consumable_item (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE backpack_item');
        $this->addSql('ALTER TABLE ruleset CHANGE backpack_capacity bagpack_capacity INT NOT NULL');
    }
}
