<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Seed untuk post id=1
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('comments')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $comments = [
            // Root comments post 1
            [
                'post_id'    => 1, 'parent_id' => null,
                'name'       => 'Budi Santoso', 'email' => 'budi@example.com',
                'comment'    => 'Artikel yang sangat informatif! Saya baru mulai belajar CodeIgniter 4 dan artikel ini sangat membantu.',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.10',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'post_id'    => 1, 'parent_id' => null,
                'name'       => 'Siti Rahayu', 'email' => 'siti@example.com',
                'comment'    => 'Terima kasih atas penjelasannya. Apakah ada tutorial lanjutan tentang CI4?',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.11',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day -5 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day -5 hours')),
            ],
            [
                'post_id'    => 1, 'parent_id' => null,
                'name'       => 'Ahmad Fauzi', 'email' => 'ahmad@example.com',
                'comment'    => 'Saya sudah mencoba mengikuti langkah-langkahnya dan berhasil! Mantap sekali tutorialnya.',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.12',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            // Reply ke comment 2 (Siti) - id akan jadi 4
            [
                'post_id'    => 1, 'parent_id' => 2,
                'name'       => 'Admin', 'email' => 'admin@mail.com',
                'comment'    => 'Halo Siti! Terima kasih sudah membaca. Ya, kami sedang menyiapkan seri tutorial CI4 yang lebih lengkap. Pantau terus blog kami ya!',
                'is_admin'   => 1, 'status' => 1, 'ip_address' => '127.0.0.1',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day -3 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day -3 hours')),
            ],
            [
                'post_id'    => 1, 'parent_id' => 2,
                'name'       => 'Dewi Lestari', 'email' => 'dewi@example.com',
                'comment'    => 'Saya juga menunggu tutorial lanjutannya! Semoga segera rilis.',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.13',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-20 hours')),
            ],
            [
                'post_id'    => 1, 'parent_id' => 2,
                'name'       => 'Riko Pratama', 'email' => 'riko@example.com',
                'comment'    => 'Setuju! Tutorial CI4 tentang REST API juga sangat dibutuhkan.',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.14',
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-18 hours')),
            ],
            // Reply ke comment 3 (Ahmad) - id akan jadi 7
            [
                'post_id'    => 1, 'parent_id' => 3,
                'name'       => 'Admin', 'email' => 'admin@mail.com',
                'comment'    => 'Senang mendengarnya Ahmad! Jika ada pertanyaan lebih lanjut, jangan ragu untuk bertanya di kolom komentar.',
                'is_admin'   => 1, 'status' => 1, 'ip_address' => '127.0.0.1',
                'created_at' => date('Y-m-d H:i:s', strtotime('-22 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-22 hours')),
            ],
            // Komentar pending (belum disetujui)
            [
                'post_id'    => 1, 'parent_id' => null,
                'name'       => 'User Baru', 'email' => 'userbaru@example.com',
                'comment'    => 'Komentar ini masih menunggu persetujuan moderator.',
                'is_admin'   => 0, 'status' => 0, 'ip_address' => '192.168.1.20',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            ],
            // Komentar spam
            [
                'post_id'    => 1, 'parent_id' => null,
                'name'       => 'SpamBot', 'email' => 'spam@mailinator.com',
                'comment'    => 'BUY CHEAP VIAGRA ONLINE CLICK HERE http://spam.com http://spam2.com http://spam3.com',
                'is_admin'   => 0, 'status' => 2, 'ip_address' => '10.0.0.1',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
            // Komentar untuk post 2
            [
                'post_id'    => 2, 'parent_id' => null,
                'name'       => 'Hendra Wijaya', 'email' => 'hendra@example.com',
                'comment'    => 'Tips yang sangat berguna! Saya langsung coba teknik stretching setiap jam. Terasa lebih segar.',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.15',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
            ],
            [
                'post_id'    => 2, 'parent_id' => null,
                'name'       => 'Maya Putri', 'email' => 'maya@example.com',
                'comment'    => 'Poin tentang tidur 7-8 jam sangat penting. Saya sering begadang dan produktivitas memang menurun.',
                'is_admin'   => 0, 'status' => 1, 'ip_address' => '192.168.1.16',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
        ];

        $this->db->table('comments')->insertBatch($comments);

        echo "✓ CommentSeeder selesai! " . count($comments) . " komentar dummy dibuat.\n";
    }
}
