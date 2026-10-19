<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;

class CommentController extends BaseController
{
    protected $helpers = ['url', 'CommentSecurity', 'CIFunctions'];

    // ── FRONTEND: Submit komentar baru ────────────────────────────────────

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 0, 'msg' => 'Forbidden']);
        }

        $postId   = (int) $this->request->getPost('post_id');
        $parentId = (int) $this->request->getPost('parent_id') ?: null;
        $name     = trim($this->request->getPost('name'));
        $email    = trim($this->request->getPost('email'));
        $text     = trim($this->request->getPost('comment'));
        $ip       = $this->request->getIPAddress();

        // Validasi dasar
        if (!$this->validate([
            'post_id' => 'required|is_natural_no_zero',
            'name'    => 'required|min_length[2]|max_length[100]',
            'email'   => 'required|valid_email',
            'comment' => 'required|min_length[5]|max_length[1000]',
        ])) {
            return $this->response->setJSON([
                'status' => 0,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Verifikasi Google reCAPTCHA
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
        if (!comment_verify_recaptcha($recaptchaResponse, $ip)) {
            return $this->response->setJSON([
                'status' => 0,
                'msg'    => 'Verifikasi reCAPTCHA gagal. Harap centang kotak "Saya bukan robot".',
            ]);
        }

        // Rate limit
        if (comment_rate_limit($ip)) {
            return $this->response->setJSON([
                'status' => 0,
                'msg'    => 'Terlalu banyak komentar. Tunggu beberapa menit.',
            ]);
        }

        // Pastikan post ada
        $post = (new Post())->find($postId);
        if (!$post) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Post tidak ditemukan.']);
        }

        // Proses keamanan
        $processed = comment_process($text, $name, $email, false);
        $status    = $processed['is_spam'] ? Comment::STATUS_SPAM : Comment::STATUS_PENDING;

        (new Comment())->insert([
            'post_id'    => $postId,
            'parent_id'  => $parentId,
            'name'       => esc($name),
            'email'      => $email,
            'comment'    => $processed['text'],
            'is_admin'   => 0,
            'status'     => $status,
            'ip_address' => $ip,
            'user_agent' => substr($this->request->getUserAgent()->getAgentString(), 0, 255),
        ]);

        $msg = $processed['is_spam']
            ? 'Komentar terdeteksi sebagai spam dan menunggu moderasi.'
            : 'Komentar berhasil dikirim dan menunggu persetujuan.';

        return $this->response->setJSON(['status' => 1, 'msg' => $msg]);
    }

    // ── BACKEND: Daftar komentar ──────────────────────────────────────────

    public function index()
    {
        $model    = new Comment();
        $filter   = $this->request->getGet('status');
        $search   = trim((string) ($this->request->getGet('q') ?? ''));
        $status   = $filter !== null && $filter !== '' ? (int) $filter : null;
        $comments = $model->getForAdmin($status, 20, $search);
        $counts   = $model->countByStatus();

        return view('backend/pages/comments', [
            'pageTitle' => 'Moderasi Komentar',
            'comments'  => $comments,
            'pager'     => $model->pager,
            'counts'    => $counts,
            'filter'    => $status,
            'search'    => $search,
        ]);
    }

    // ── BACKEND: Approve ──────────────────────────────────────────────────

    public function approve($id)
    {
        (new Comment())->update($id, ['status' => Comment::STATUS_APPROVED]);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 1, 'msg' => 'Komentar disetujui.']);
        }
        return redirect()->back()->with('success', 'Komentar disetujui.');
    }

    // ── BACKEND: Tandai spam ──────────────────────────────────────────────

    public function spam($id)
    {
        (new Comment())->update($id, ['status' => Comment::STATUS_SPAM]);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 1, 'msg' => 'Komentar ditandai sebagai spam.']);
        }
        return redirect()->back()->with('success', 'Komentar ditandai spam.');
    }

    // ── BACKEND: Trash ────────────────────────────────────────────────────

    public function trash($id)
    {
        (new Comment())->update($id, ['status' => Comment::STATUS_TRASH]);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 1, 'msg' => 'Komentar dipindahkan ke kotak sampah.']);
        }
        return redirect()->back()->with('success', 'Komentar dipindah ke trash.');
    }

    // ── BACKEND: Hapus permanen ───────────────────────────────────────────

    public function destroy($id)
    {
        (new Comment())->delete($id);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 1, 'msg' => 'Komentar dihapus permanen.']);
        }
        return redirect()->back()->with('success', 'Komentar dihapus permanen.');
    }

    // ── BACKEND: Balas komentar (admin) ───────────────────────────────────

    public function reply()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 0]);
        }

        $parentId = (int) $this->request->getPost('parent_id');
        $postId   = (int) $this->request->getPost('post_id');
        $text     = trim($this->request->getPost('comment'));

        if (empty($text) || !$postId || !$parentId) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Data tidak lengkap.']);
        }

        // Ambil nama & email admin yang sedang login secara dinamis
        $currentUser = get_user();
        $adminName   = $currentUser ? $currentUser->name : 'Administrator';
        $adminEmail  = $currentUser ? $currentUser->email : get_settings()->blog_email;

        $processed = comment_process($text, $adminName, '', true);

        (new Comment())->insert([
            'post_id'    => $postId,
            'parent_id'  => $parentId,
            'name'       => $adminName,
            'email'      => $adminEmail,
            'comment'    => $processed['text'],
            'is_admin'   => 1,
            'status'     => Comment::STATUS_APPROVED,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => substr($this->request->getUserAgent()->getAgentString(), 0, 255),
        ]);

        return $this->response->setJSON(['status' => 1, 'msg' => 'Balasan berhasil dikirim.']);
    }

    // ── BACKEND: Bulk action ──────────────────────────────────────────────

    public function bulk()
    {
        $ids    = $this->request->getPost('ids') ?? [];
        $action = $this->request->getPost('action');
        $model  = new Comment();

        if (empty($ids)) {
            return redirect()->back()->with('fail', 'Pilih komentar terlebih dahulu.');
        }

        foreach ($ids as $id) {
            match ($action) {
                'approve' => $model->update($id, ['status' => Comment::STATUS_APPROVED]),
                'spam'    => $model->update($id, ['status' => Comment::STATUS_SPAM]),
                'trash'   => $model->update($id, ['status' => Comment::STATUS_TRASH]),
                'delete'  => $model->delete($id),
                default   => null,
            };
        }

        return redirect()->back()->with('success', 'Aksi bulk berhasil dijalankan.');
    }

    // ── BACKEND: Kosongkan Trash / Spam ───────────────────────────────────

    public function emptyTrash()
    {
        $target = $this->request->getPost('target') ?? 'trash';
        $status = ($target === 'spam') ? Comment::STATUS_SPAM : Comment::STATUS_TRASH;

        (new Comment())->where('status', $status)->delete();

        $label = ($target === 'spam') ? 'folder Spam' : 'Kotak Sampah (Trash)';
        return redirect()->back()->with('success', "Semua komentar di {$label} berhasil dibersihkan.");
    }
}
