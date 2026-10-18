<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthorTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'bio'        => ['type' => 'TEXT', 'null' => true],
            'photo'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'content_type_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('authors');
    }

    public function down()
    {
        $this->forge->dropTable('authors');
    }
}