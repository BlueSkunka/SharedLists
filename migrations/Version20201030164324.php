<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201030164324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE listing_item_user (listing_item_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F59576F2D512911 (listing_item_id), INDEX IDX_F59576F2A76ED395 (user_id), PRIMARY KEY(listing_item_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listing_item_user ADD CONSTRAINT FK_F59576F2D512911 FOREIGN KEY (listing_item_id) REFERENCES listing_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listing_item_user ADD CONSTRAINT FK_F59576F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_request ADD CONSTRAINT FK_531976C31ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id)');
        $this->addSql('CREATE INDEX IDX_531976C31ED93D47 ON user_group_request (user_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE listing_item_user');
        $this->addSql('ALTER TABLE user_group_request DROP FOREIGN KEY FK_531976C31ED93D47');
        $this->addSql('DROP INDEX IDX_531976C31ED93D47 ON user_group_request');
    }
}
