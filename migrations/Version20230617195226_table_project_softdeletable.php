<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617195226_table_project_softdeletable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add softdeletable behaviour to project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            alter table project
                add column deleted_at timestamptz
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<SQL
            alter table project
                drop column deleted_at
        SQL);
    }
}
