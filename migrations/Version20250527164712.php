<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527164712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE conge RENAME INDEX idx_2ed89348a76ed395 TO IDX_2ED893483F192FC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ADD actif TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE session DROP actif
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conge RENAME INDEX idx_2ed893483f192fc TO IDX_2ED89348A76ED395
        SQL);
    }
}
