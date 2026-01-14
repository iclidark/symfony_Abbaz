<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114135021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE task_history CHANGE task_id task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task_history ADD CONSTRAINT FK_385B5AA18DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP status');
        $this->addSql('ALTER TABLE task_history DROP FOREIGN KEY FK_385B5AA18DB60186');
        $this->addSql('ALTER TABLE task_history CHANGE task_id task_id INT NOT NULL');
    }
}
