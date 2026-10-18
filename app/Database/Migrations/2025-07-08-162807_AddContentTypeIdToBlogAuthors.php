<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// NOTE: Kolom 'content_type_id' sudah ada di tabel 'authors' sejak CreateAuthorTable.
// Migration ini dikosongkan untuk menghindari error duplikasi kolom.
class AddContentTypeIdToBlogAuthors extends Migration
{
    public function up()
    {
        // Kolom content_type_id sudah ada di tabel authors sejak awal
    }

    public function down()
    {
        // Tidak ada yang perlu di-rollback
    }
}
