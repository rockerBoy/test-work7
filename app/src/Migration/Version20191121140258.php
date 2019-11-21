<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191121140258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Статусы и редактирование админом';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('tasks');
        $table->dropColumn('finished_at');
        $table->addColumn('status', Types::BOOLEAN, [])->setDefault(false);
        $table->addColumn('is_edit', Types::BOOLEAN)->setDefault(false);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('tasks');
        $table->addColumn('finished_at', Types::DATETIME_IMMUTABLE);
        $table->dropColumn('status');
        $table->dropColumn('is_edit');
    }
}
