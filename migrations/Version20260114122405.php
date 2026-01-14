<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114122405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_history (id INT AUTO_INCREMENT NOT NULL, changed_at DATETIME NOT NULL, action VARCHAR(255) NOT NULL, task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_385B5AA18DB60186 (task_id), INDEX IDX_385B5AA1A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE task_history ADD CONSTRAINT FK_385B5AA18DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task_history ADD CONSTRAINT FK_385B5AA1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task DROP updated_at, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE name title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_history DROP FOREIGN KEY FK_385B5AA18DB60186');
        $this->addSql('ALTER TABLE task_history DROP FOREIGN KEY FK_385B5AA1A76ED395');
        $this->addSql('DROP TABLE task_history');
        $this->addSql('ALTER TABLE task ADD updated_at DATETIME NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE title name VARCHAR(255) NOT NULL');
    }
}
