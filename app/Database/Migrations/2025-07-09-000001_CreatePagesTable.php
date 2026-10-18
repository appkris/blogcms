<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'content'        => ['type' => 'TEXT'],
            'show_in_footer' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'status'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'ordering'       => ['type' => 'INT', 'constraint' => 11, 'default' => 100],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('pages');
    }

    public function down()
    {
        $this->forge->dropTable('pages');
    }
}
