<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004191001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_group_request ADD user_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_group_request ADD CONSTRAINT FK_531976C31ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id)');
        $this->addSql('CREATE INDEX IDX_531976C31ED93D47 ON user_group_request (user_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_group_request DROP FOREIGN KEY FK_531976C31ED93D47');
        $this->addSql('DROP INDEX IDX_531976C31ED93D47 ON user_group_request');
        $this->addSql('ALTER TABLE user_group_request DROP user_group_id');
    }
}
