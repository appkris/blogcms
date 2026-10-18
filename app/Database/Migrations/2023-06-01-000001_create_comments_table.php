<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// NOTE: Migration ini dipindahkan ke 2024-05-23-000001_CreateCommentsTable.php
// agar urutan eksekusi benar (setelah tabel posts dibuat).
class CreateCommentsTable_Old extends Migration
{
    public function up()
    {
        // Dipindahkan ke 2024-05-23-000001_CreateCommentsTable.php
    }

    public function down()
    {
        // Tidak ada yang perlu di-rollback
    }
}
