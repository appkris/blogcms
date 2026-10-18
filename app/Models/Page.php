<?php

namespace App\Models;

use CodeIgniter\Model;

class Page extends Model
{
    protected $table         = 'pages';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['title', 'slug', 'content', 'show_in_footer', 'status', 'ordering'];
    protected $useTimestamps = true;
}
