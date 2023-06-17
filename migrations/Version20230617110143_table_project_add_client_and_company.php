<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617110143_table_project_add_client_and_company extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add client and company fields to project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            alter table project
                add column client text,
                add column company text
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<SQL
            alter table project
                drop column client,
                drop column company
        SQL);
    }
}
