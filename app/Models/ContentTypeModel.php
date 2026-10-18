<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentTypeModel extends Model
{
    protected $table = 'content_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'bio', 'content_type_id'];
    public $timestamps = false;
}