<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCommentsAddParentStatus extends Migration
{
    public function up()
    {
        // parent_id untuk nested comment (NULL = root comment)
        $this->forge->addColumn('comments', [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'post_id',
            ],
        ]);

        // status: pending=0, approved=1, spam=2, trash=3
        $this->forge->addColumn('comments', [
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'is_admin',
            ],
        ]);

        $this->forge->addColumn('comments', [
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
                'after'      => 'status',
            ],
        ]);

        $this->forge->addColumn('comments', [
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'ip_address',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('comments', ['parent_id', 'status', 'ip_address', 'user_agent']);
    }
}
