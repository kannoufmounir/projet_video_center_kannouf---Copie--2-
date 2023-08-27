<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807092603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'imageName';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video ADD image_name VARCHAR(500) DEFAULT NULL, CHANGE video_link video_link VARCHAR(500) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP image_name, CHANGE video_link video_link VARCHAR(500) DEFAULT NULL');
    }
}
