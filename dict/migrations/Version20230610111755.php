<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610111755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B5E357438D');
        $this->addSql('DROP INDEX IDX_D4DB71B5E357438D ON language');
        $this->addSql('ALTER TABLE language DROP word_id');
        $this->addSql('ALTER TABLE word ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F1751182F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_C3F1751182F1BAF4 ON word (language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language ADD word_id INT NOT NULL');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B5E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('CREATE INDEX IDX_D4DB71B5E357438D ON language (word_id)');
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F1751182F1BAF4');
        $this->addSql('DROP INDEX IDX_C3F1751182F1BAF4 ON word');
        $this->addSql('ALTER TABLE word DROP language_id');
    }
}
