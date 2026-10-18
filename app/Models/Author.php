<?php

namespace App\Models;

use CodeIgniter\Model;

class Author extends Model
{
    protected $table      = 'authors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'bio', 'photo', 'content_type_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}