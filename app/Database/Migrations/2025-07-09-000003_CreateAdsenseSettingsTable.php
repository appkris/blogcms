<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdsenseSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'publisher_id' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'is_active'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'slot_header'  => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'slot_sidebar' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'slot_content' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'show_header'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'show_sidebar' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'show_content' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('adsense_settings');
    }

    public function down()
    {
        $this->forge->dropTable('adsense_settings');
    }
}
