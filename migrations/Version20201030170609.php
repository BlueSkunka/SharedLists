<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201030170609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, buyer_id INT NOT NULL, listing_item_id INT NOT NULL, main_purchase TINYINT(1) NOT NULL, state TINYINT(1) NOT NULL, share INT NOT NULL, INDEX IDX_6117D13B6C755722 (buyer_id), INDEX IDX_6117D13BD512911 (listing_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B6C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BD512911 FOREIGN KEY (listing_item_id) REFERENCES listing_item (id)');
        $this->addSql('DROP TABLE listing_item_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE listing_item_user (listing_item_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F59576F2A76ED395 (user_id), INDEX IDX_F59576F2D512911 (listing_item_id), PRIMARY KEY(listing_item_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE listing_item_user ADD CONSTRAINT FK_F59576F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listing_item_user ADD CONSTRAINT FK_F59576F2D512911 FOREIGN KEY (listing_item_id) REFERENCES listing_item (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE purchase');
    }
}
