<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171221091721 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chapter_special_item (chapter_id INT NOT NULL, special_item_id INT NOT NULL, INDEX IDX_38F46221579F4768 (chapter_id), INDEX IDX_38F46221CA630ECB (special_item_id), PRIMARY KEY(chapter_id, special_item_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter_consumable_item (chapter_id INT NOT NULL, consumable_item_id INT NOT NULL, INDEX IDX_6327134C579F4768 (chapter_id), INDEX IDX_6327134C93A55194 (consumable_item_id), PRIMARY KEY(chapter_id, consumable_item_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter_special_item ADD CONSTRAINT FK_38F46221579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter_special_item ADD CONSTRAINT FK_38F46221CA630ECB FOREIGN KEY (special_item_id) REFERENCES special_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter_consumable_item ADD CONSTRAINT FK_6327134C579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter_consumable_item ADD CONSTRAINT FK_6327134C93A55194 FOREIGN KEY (consumable_item_id) REFERENCES consumable_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter ADD text_content2 LONGTEXT NOT NULL, CHANGE text_content text_content1 LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE ruleset ADD max_weapon_carried INT NOT NULL, ADD bagpack_capacity INT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE chapter_special_item');
        $this->addSql('DROP TABLE chapter_consumable_item');
        $this->addSql('ALTER TABLE chapter ADD text_content LONGTEXT NOT NULL COLLATE utf8_unicode_ci, DROP text_content1, DROP text_content2');
        $this->addSql('ALTER TABLE ruleset DROP max_weapon_carried, DROP bagpack_capacity');
    }
}
