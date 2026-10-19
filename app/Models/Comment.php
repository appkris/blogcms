<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
    protected $table         = 'comments';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'post_id', 'parent_id', 'name', 'email',
        'comment', 'is_admin', 'status', 'ip_address', 'user_agent',
    ];
    protected $useTimestamps = true;

    // Status constants
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_SPAM     = 2;
    const STATUS_TRASH    = 3;

    /**
     * Ambil semua komentar approved untuk satu post, sudah disusun nested.
     * Return: array root comments, masing-masing punya key 'replies'
     */
    public function getNestedByPost(int $postId): array
    {
        $rows = $this->where('post_id', $postId)
                     ->where('status', self::STATUS_APPROVED)
                     ->orderBy('created_at', 'ASC')
                     ->asObject()
                     ->findAll();

        return $this->buildTree($rows);
    }

    public function buildTree(array $rows, int $parentId = 0): array
    {
        $tree = [];
        foreach ($rows as $row) {
            $pid = (int) ($row->parent_id ?? 0);
            if ($pid === $parentId) {
                // Hanya 1 level: replies tidak bisa di-reply lagi
                // Semua reply ke reply dianggap reply ke root-nya
                $row->replies = [];
                if ($parentId === 0) {
                    // Kumpulkan semua yang parent_id = id ini (level 1)
                    foreach ($rows as $r) {
                        if ((int)($r->parent_id ?? 0) === (int)$row->id) {
                            $r->replies = [];
                            $row->replies[] = $r;
                        }
                    }
                }
                $tree[] = $row;
            }
        }
        return $tree;
    }

    /** Hitung komentar approved untuk satu post */
    public function countApproved(int $postId): int
    {
        return $this->where('post_id', $postId)
                    ->where('status', self::STATUS_APPROVED)
                    ->countAllResults();
    }

    /** Ambil semua untuk admin dengan filter status & search opsional */
    public function getForAdmin(?int $status = null, int $perPage = 20, string $search = ''): array
    {
        $q = $this->select('comments.*, posts.title as post_title, posts.slug as post_slug')
                  ->join('posts', 'posts.id = comments.post_id', 'left')
                  ->orderBy('comments.created_at', 'DESC');

        if ($status !== null) {
            $q->where('comments.status', $status);
        }

        if (!empty($search)) {
            $q->groupStart()
              ->like('comments.name', $search)
              ->orLike('comments.email', $search)
              ->orLike('comments.comment', $search)
              ->orLike('posts.title', $search)
              ->groupEnd();
        }

        return $q->paginate($perPage);
    }

    public function countByStatus(): array
    {
        $rows = $this->select('status, COUNT(*) as total')
                     ->groupBy('status')
                     ->findAll();
        $result = [0 => 0, 1 => 0, 2 => 0, 3 => 0];
        foreach ($rows as $r) {
            $result[(int)$r['status']] = (int)$r['total'];
        }
        return $result;
    }
}
