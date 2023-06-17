<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617205718_table_task_softdeletable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add softdeleteable behaviour to task';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            alter table task
                add column deleted_at timestamptz
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<SQL
            alter table task
                drop column deleted_at
        SQL);
    }
}
