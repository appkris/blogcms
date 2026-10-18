<?php

namespace App\Models;

use CodeIgniter\Model;

class AdsenseSetting extends Model
{
    protected $table         = 'adsense_settings';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'publisher_id', 'is_active',
        'slot_header', 'slot_sidebar', 'slot_content',
        'show_header', 'show_sidebar', 'show_content',
    ];
    protected $useTimestamps = true;

    /** Ambil satu-satunya row settings, buat jika belum ada */
    public function getSettings(): object
    {
        $row = $this->asObject()->first();
        if (!$row) {
            $this->insert(['is_active' => 0]);
            $row = $this->asObject()->first();
        }
        return $row;
    }
}
