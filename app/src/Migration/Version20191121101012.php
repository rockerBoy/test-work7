<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191121101012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Создадим таблицу с задачами';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('tasks');
        $table->addColumn("id", "integer", [
            "unsigned" => true,
            'autoincrement' => true
        ]);
        $table->addColumn("username", "string", ["length" => 45]);
        $table->addColumn("email", "string", ["length" => 45]);
        $table->addColumn("description", "text");
        $table->addColumn("finished_at", "datetime");
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('tasks');
    }
}
