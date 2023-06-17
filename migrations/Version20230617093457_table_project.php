<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617093457_table_project extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create project table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create table project (
                project_id bigserial not null primary key,
                title text not null,
                description text not null,
                status text check (status in ('new', 'pending', 'failed', 'done')),
                -- duration daterange not null -- proper type
                start_at timestamptz not null,
                end_at timestamptz not null check (end_at > start_at)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table project');
    }
}
