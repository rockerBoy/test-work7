<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191121111344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'finished at nullable';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('tasks');
        $table->getColumn('finished_at')->setNotnull(false);

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('tasks');
        $table->getColumn('finished_at')->setNotnull(true);
    }
}
