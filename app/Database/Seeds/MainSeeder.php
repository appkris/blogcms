<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // ── 1. USERS (admin) ──────────────────────────────────────────────
        $this->db->table('users')->truncate();
        $this->db->table('users')->insert([
            'name'       => 'Administrator',
            'username'   => 'admin',
            'email'      => 'admin@mail.com',
            'password'   => password_hash('Admin@123', PASSWORD_BCRYPT),
            'picture'    => null,
            'bio'        => 'Admin blog CI4.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ── 2. SETTINGS ───────────────────────────────────────────────────
        $this->db->table('settings')->truncate();
        $this->db->table('settings')->insert([
            'blog_title'            => 'Blog AppKris',
            'blog_email'            => 'admin@mail.com',
            'blog_phone'            => '+62 812-3456-7890',
            'blog_meta_keywords'    => 'blog, teknologi, gaya hidup, kesehatan',
            'blog_meta_description' => 'Blog AppKris - Berbagi informasi seputar teknologi, gaya hidup, dan kesehatan.',
            'blog_logo'             => 'dummy_logo.jpg',
            'blog_favicon'          => 'dummy_favicon.jpg',
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        // ── 3. SOCIAL MEDIA ───────────────────────────────────────────────
        $this->db->table('social_media')->truncate();
        $this->db->table('social_media')->insert([
            'facebook_url'  => 'https://facebook.com/blogappkris',
            'twitter_url'   => 'https://twitter.com/blogappkris',
            'instagram_url' => 'https://instagram.com/blogappkris',
            'youtube_url'   => 'https://youtube.com/@blogappkris',
            'tiktok_url'    => 'https://tiktok.com/@blogappkris',
            'linkedin_url'  => 'https://linkedin.com/in/blogappkris',
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // ── 4. AUTHOR ─────────────────────────────────────────────────────
        $this->db->table('authors')->truncate();
        $this->db->table('authors')->insert([
            'name'            => 'Kris Blogger',
            'bio'             => 'Penulis dan penggiat teknologi. Suka berbagi tentang dunia digital, gaya hidup sehat, dan kuliner nusantara.',
            'photo'           => 'dummy_author_photo.jpg',
            'content_type_id' => 3,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        // ── 5. CATEGORIES ─────────────────────────────────────────────────
        $this->db->table('categories')->truncate();
        $categories = [
            ['name' => 'Teknologi',          'ordering' => 1,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gaya Hidup',         'ordering' => 2,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kesehatan',          'ordering' => 3,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pendidikan',         'ordering' => 4,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kuliner',            'ordering' => 5,  'created_at' => $now, 'updated_at' => $now],
        ];
        $this->db->table('categories')->insertBatch($categories);

        // ── 6. SUB CATEGORIES ─────────────────────────────────────────────
        $this->db->table('sub_categories')->truncate();
        $subCategories = [
            ['name' => 'Pemrograman',        'slug' => 'pemrograman',        'parent_cat' => 1, 'description' => 'Artikel seputar coding dan pemrograman.',    'ordering' => 1,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gadget & Review',    'slug' => 'gadget-review',      'parent_cat' => 1, 'description' => 'Review gadget terbaru.',                     'ordering' => 2,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kecantikan',         'slug' => 'kecantikan',         'parent_cat' => 2, 'description' => 'Tips kecantikan dan perawatan diri.',        'ordering' => 3,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Olahraga',           'slug' => 'olahraga',           'parent_cat' => 2, 'description' => 'Gaya hidup aktif dan olahraga.',             'ordering' => 4,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nutrisi & Diet',     'slug' => 'nutrisi-diet',       'parent_cat' => 3, 'description' => 'Panduan nutrisi dan diet sehat.',            'ordering' => 5,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tips Belajar',       'slug' => 'tips-belajar',       'parent_cat' => 4, 'description' => 'Strategi dan tips belajar efektif.',         'ordering' => 6,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Resep Masakan',      'slug' => 'resep-masakan',      'parent_cat' => 5, 'description' => 'Resep masakan nusantara dan mancanegara.',   'ordering' => 7,  'created_at' => $now, 'updated_at' => $now],
        ];
        $this->db->table('sub_categories')->insertBatch($subCategories);

        // ── 7. POSTS ──────────────────────────────────────────────────────
        $this->db->table('posts')->truncate();
        $posts = [
            [
                'author_id'        => 1,
                'category_id'      => 1,
                'title'            => 'Mengenal CodeIgniter 4: Framework PHP Modern',
                'slug'             => 'mengenal-codeigniter-4-framework-php-modern',
                'content'          => '<p>CodeIgniter 4 adalah framework PHP yang ringan, cepat, dan fleksibel. Dirancang untuk developer yang membutuhkan toolkit sederhana namun powerful untuk membangun aplikasi web full-featured.</p><p>Dengan arsitektur MVC yang bersih, CI4 memudahkan pemisahan logika bisnis dari tampilan. Fitur unggulannya meliputi routing yang fleksibel, ORM bawaan, sistem migrasi database, dan banyak lagi.</p><p>Dalam artikel ini kita akan membahas cara instalasi, konfigurasi dasar, dan membuat halaman pertama dengan CI4.</p>',
                'featured_image'   => 'pimg_dummy_post1.jpg',
                'tags'             => 'codeigniter,php,framework,web development',
                'meta_keywords'    => 'codeigniter 4, php framework, mvc',
                'meta_description' => 'Panduan lengkap mengenal CodeIgniter 4 framework PHP modern untuk pemula.',
                'visibility'       => 1,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-6 days')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
            [
                'author_id'        => 1,
                'category_id'      => 2,
                'title'            => '10 Tips Gaya Hidup Sehat untuk Programmer',
                'slug'             => '10-tips-gaya-hidup-sehat-untuk-programmer',
                'content'          => '<p>Sebagai programmer, kita sering menghabiskan berjam-jam di depan layar. Ini bisa berdampak buruk pada kesehatan jika tidak diimbangi dengan gaya hidup sehat.</p><p>Berikut 10 tips yang bisa langsung kamu terapkan: rutin stretching setiap jam, minum air putih minimal 2 liter sehari, atur postur duduk yang ergonomis, istirahat mata dengan aturan 20-20-20, dan tidur cukup 7-8 jam per malam.</p><p>Dengan menerapkan kebiasaan sehat ini, produktivitas coding kamu justru akan meningkat signifikan.</p>',
                'featured_image'   => 'pimg_dummy_post2.jpg',
                'tags'             => 'gaya hidup,kesehatan,programmer,produktivitas',
                'meta_keywords'    => 'gaya hidup sehat, programmer, tips kesehatan',
                'meta_description' => '10 tips gaya hidup sehat yang wajib diterapkan oleh programmer agar tetap produktif.',
                'visibility'       => 1,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'author_id'        => 1,
                'category_id'      => 5,
                'title'            => 'Nutrisi & Diet Seimbang untuk Tubuh Ideal',
                'slug'             => 'nutrisi-diet-seimbang-untuk-tubuh-ideal',
                'content'          => '<p>Menjaga pola makan seimbang adalah kunci utama untuk mendapatkan tubuh ideal dan kesehatan optimal. Bukan soal diet ketat, melainkan tentang memilih makanan yang tepat.</p><p>Pastikan setiap piring makan kamu terdiri dari 50% sayur dan buah, 25% protein (ayam, ikan, tahu, tempe), dan 25% karbohidrat kompleks (nasi merah, ubi, oat).</p><p>Hindari makanan ultra-proses, minuman manis, dan lemak trans. Gantikan dengan makanan alami yang kaya serat dan nutrisi.</p>',
                'featured_image'   => 'pimg_dummy_post3.jpg',
                'tags'             => 'nutrisi,diet,kesehatan,makanan sehat',
                'meta_keywords'    => 'nutrisi seimbang, diet sehat, tubuh ideal',
                'meta_description' => 'Panduan nutrisi dan diet seimbang untuk mendapatkan tubuh ideal secara alami.',
                'visibility'       => 1,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'author_id'        => 1,
                'category_id'      => 6,
                'title'            => 'Strategi Belajar Efektif dengan Teknik Pomodoro',
                'slug'             => 'strategi-belajar-efektif-dengan-teknik-pomodoro',
                'content'          => '<p>Teknik Pomodoro adalah metode manajemen waktu yang dikembangkan oleh Francesco Cirillo pada akhir 1980-an. Metode ini menggunakan timer untuk membagi pekerjaan menjadi interval 25 menit, dipisahkan oleh istirahat singkat.</p><p>Cara kerjanya sederhana: pilih tugas yang ingin dikerjakan, set timer 25 menit, kerjakan tugas tanpa gangguan, istirahat 5 menit, dan ulangi. Setelah 4 sesi, ambil istirahat panjang 15-30 menit.</p><p>Teknik ini terbukti meningkatkan fokus, mengurangi kelelahan mental, dan meningkatkan produktivitas belajar secara signifikan.</p>',
                'featured_image'   => 'pimg_dummy_post4.jpg',
                'tags'             => 'belajar,produktivitas,pomodoro,pendidikan',
                'meta_keywords'    => 'teknik pomodoro, belajar efektif, manajemen waktu',
                'meta_description' => 'Pelajari teknik Pomodoro untuk meningkatkan efektivitas belajar dan produktivitas.',
                'visibility'       => 1,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'author_id'        => 1,
                'category_id'      => 7,
                'title'            => 'Resep Nasi Goreng Spesial ala Restoran',
                'slug'             => 'resep-nasi-goreng-spesial-ala-restoran',
                'content'          => '<p>Nasi goreng adalah salah satu makanan paling populer di Indonesia. Dengan bahan sederhana, kamu bisa membuat nasi goreng yang rasanya tidak kalah dengan restoran berbintang.</p><p>Bahan: 2 piring nasi putih dingin, 2 butir telur, 3 siung bawang putih, 5 siung bawang merah, 2 sdm kecap manis, 1 sdm saus tiram, garam, merica, dan minyak goreng.</p><p>Cara membuat: tumis bawang hingga harum, masukkan telur orak-arik, tambahkan nasi, bumbui dengan kecap dan saus tiram, aduk rata hingga matang. Sajikan dengan kerupuk dan acar.</p>',
                'featured_image'   => 'pimg_dummy_post5.jpg',
                'tags'             => 'resep,kuliner,nasi goreng,masakan indonesia',
                'meta_keywords'    => 'resep nasi goreng, masakan indonesia, kuliner',
                'meta_description' => 'Resep nasi goreng spesial ala restoran yang mudah dibuat di rumah.',
                'visibility'       => 1,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'author_id'        => 1,
                'category_id'      => 2,
                'title'            => 'Review Gadget: Smartphone Terbaik 2025',
                'slug'             => 'review-gadget-smartphone-terbaik-2025',
                'content'          => '<p>Tahun 2025 menghadirkan banyak smartphone flagship dengan spesifikasi yang semakin memukau. Dari layar AMOLED 120Hz, chipset terbaru, hingga kamera dengan kemampuan AI yang canggih.</p><p>Dalam review kali ini, kami membandingkan tiga smartphone terbaik dari segi performa, kamera, baterai, dan harga. Setiap perangkat diuji dalam kondisi nyata selama dua minggu penuh.</p><p>Hasilnya cukup mengejutkan — smartphone mid-range ternyata mampu bersaing ketat dengan flagship dalam beberapa kategori penting.</p>',
                'featured_image'   => 'pimg_dummy_post6.jpg',
                'tags'             => 'gadget,smartphone,review,teknologi',
                'meta_keywords'    => 'review smartphone 2025, gadget terbaik, flagship',
                'meta_description' => 'Review dan perbandingan smartphone terbaik tahun 2025 dari berbagai merek.',
                'visibility'       => 1,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at'       => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'author_id'        => 1,
                'category_id'      => 3,
                'title'            => 'Tips Perawatan Kulit Wajah untuk Pria',
                'slug'             => 'tips-perawatan-kulit-wajah-untuk-pria',
                'content'          => '<p>Perawatan kulit bukan hanya untuk wanita. Pria juga perlu menjaga kesehatan kulit wajah agar tetap bersih, sehat, dan terawat. Rutinitas skincare pria tidak harus rumit.</p><p>Langkah dasar yang perlu dilakukan setiap hari: cuci muka 2x sehari dengan facial wash yang sesuai jenis kulit, gunakan toner untuk menyeimbangkan pH kulit, aplikasikan moisturizer, dan jangan lupa sunscreen di pagi hari.</p><p>Dengan konsistensi selama 30 hari, kamu akan melihat perbedaan yang signifikan pada tekstur dan kecerahan kulit wajah.</p>',
                'featured_image'   => 'pimg_dummy_post7.jpg',
                'tags'             => 'skincare,kecantikan,perawatan kulit,pria',
                'meta_keywords'    => 'skincare pria, perawatan kulit wajah, tips kecantikan',
                'meta_description' => 'Panduan lengkap perawatan kulit wajah untuk pria agar tampil lebih percaya diri.',
                'visibility'       => 1,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];
        $this->db->table('posts')->insertBatch($posts);

        // ── 8. PAGES ──────────────────────────────────────────────────────────
        $this->db->table('pages')->truncate();
        $pages = [
            [
                'title'          => 'Tentang Kami',
                'slug'           => 'tentang-kami',
                'content'        => '<h2>Tentang Blog AppKris</h2><p>Blog AppKris adalah platform berbagi informasi seputar teknologi, gaya hidup, kesehatan, dan kuliner nusantara. Kami hadir untuk memberikan konten berkualitas yang informatif dan bermanfaat bagi pembaca.</p><p>Didirikan pada tahun 2024, kami berkomitmen untuk terus menghadirkan artikel-artikel terpercaya yang ditulis oleh para penulis berpengalaman di bidangnya.</p>',
                'show_in_footer' => 1,
                'status'         => 1,
                'ordering'       => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'Kebijakan Privasi',
                'slug'           => 'kebijakan-privasi',
                'content'        => '<h2>Kebijakan Privasi</h2><p>Kami menghargai privasi Anda. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.</p><h3>Informasi yang Kami Kumpulkan</h3><p>Kami dapat mengumpulkan informasi seperti nama, alamat email, dan data penggunaan situs ketika Anda berinteraksi dengan blog kami.</p><h3>Penggunaan Informasi</h3><p>Informasi yang dikumpulkan digunakan untuk meningkatkan pengalaman pengguna, mengirimkan newsletter (jika Anda berlangganan), dan menganalisis trafik situs.</p><h3>Keamanan Data</h3><p>Kami menggunakan langkah-langkah keamanan yang wajar untuk melindungi informasi Anda dari akses yang tidak sah.</p>',
                'show_in_footer' => 1,
                'status'         => 1,
                'ordering'       => 2,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'Syarat & Ketentuan',
                'slug'           => 'syarat-ketentuan',
                'content'        => '<h2>Syarat & Ketentuan</h2><p>Dengan mengakses dan menggunakan blog ini, Anda menyetujui syarat dan ketentuan berikut.</p><h3>Penggunaan Konten</h3><p>Seluruh konten di blog ini dilindungi hak cipta. Dilarang menyalin, mendistribusikan, atau memodifikasi konten tanpa izin tertulis dari kami.</p><h3>Tanggung Jawab</h3><p>Kami tidak bertanggung jawab atas kerugian yang timbul akibat penggunaan informasi yang tersedia di blog ini.</p><h3>Perubahan Ketentuan</h3><p>Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>',
                'show_in_footer' => 1,
                'status'         => 1,
                'ordering'       => 3,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'Hubungi Kami',
                'slug'           => 'hubungi-kami',
                'content'        => '<h2>Hubungi Kami</h2><p>Kami senang mendengar dari Anda! Jika Anda memiliki pertanyaan, saran, atau ingin berkolaborasi, jangan ragu untuk menghubungi kami.</p><ul><li><strong>Email:</strong> admin@mail.com</li><li><strong>Telepon:</strong> +62 812-3456-7890</li><li><strong>Jam Kerja:</strong> Senin - Jumat, 09.00 - 17.00 WIB</li></ul><p>Kami akan merespons pesan Anda dalam 1-2 hari kerja.</p>',
                'show_in_footer' => 1,
                'status'         => 1,
                'ordering'       => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'Disclaimer',
                'slug'           => 'disclaimer',
                'content'        => '<h2>Disclaimer</h2><p>Informasi yang disajikan di blog ini hanya untuk tujuan edukasi dan informasi umum. Kami tidak memberikan jaminan atas keakuratan, kelengkapan, atau kesesuaian informasi tersebut untuk tujuan tertentu.</p><p>Pembaca disarankan untuk selalu memverifikasi informasi dari sumber terpercaya sebelum mengambil keputusan berdasarkan konten yang ada di blog ini.</p>',
                'show_in_footer' => 0,
                'status'         => 1,
                'ordering'       => 5,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];
        $this->db->table('pages')->insertBatch($pages);

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "✓ Seeder selesai!\n";
        echo "  Login: admin@mail.com / Admin@123\n";
        echo "  URL  : http://localhost:8080/admin/login\n";
    }
}
