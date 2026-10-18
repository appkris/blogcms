<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->truncate();
        $this->db->table('users')->insert([
            'name'       => 'Administrator',
            'username'   => 'admin',
            'email'      => 'admin@mail.com',
            'password'   => password_hash('Admin@123', PASSWORD_BCRYPT),
            'picture'    => null,
            'bio'        => 'Admin blog CI4.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
