<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230808123236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE video MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON video');
        $this->addSql('ALTER TABLE video ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE video RENAME INDEX user_id TO IDX_7CC7DA2CA76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP is_verified');
        $this->addSql('ALTER TABLE video MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON video');
        $this->addSql('ALTER TABLE video ADD PRIMARY KEY (id, user_id)');
        $this->addSql('ALTER TABLE video RENAME INDEX idx_7cc7da2ca76ed395 TO user_id');
    }
}
