<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424221538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, operateur_id INT DEFAULT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, type VARCHAR(20) DEFAULT NULL, INDEX IDX_D499BFF63F192FC (operateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning ADD CONSTRAINT FK_D499BFF63F192FC FOREIGN KEY (operateur_id) REFERENCES operateur (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF63F192FC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE planning
        SQL);
    }
}
