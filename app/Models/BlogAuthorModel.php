<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogAuthorModel extends Model
{
    protected $table      = 'blog_authors';
    protected $primaryKey = 'id';

    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'bio', 'photo', 'content_type_id'];

}