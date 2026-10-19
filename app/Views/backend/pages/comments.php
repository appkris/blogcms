<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<?php $adminAvatarUrl = get_admin_avatar_url(36); ?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <div class="title"><h4>Moderasi Komentar</h4></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Beranda</a></li>
                    <li class="breadcrumb-item active">Komentar</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show py-2">
    <i class="fa fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif ?>
<?php if (session()->getFlashdata('fail')): ?>
<div class="alert alert-danger alert-dismissible fade show py-2">
    <i class="fa fa-exclamation-circle mr-1"></i> <?= session()->getFlashdata('fail') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif ?>

<!-- Stat cards -->
<div class="row mb-4">
    <?php
    $statCards = [
        ['label' => 'Pending',  'key' => 0, 'color' => '#f59e0b', 'icon' => 'fa-clock-o',       'qs' => '?status=0'],
        ['label' => 'Approved', 'key' => 1, 'color' => '#10b981', 'icon' => 'fa-check-circle', 'qs' => '?status=1'],
        ['label' => 'Spam',     'key' => 2, 'color' => '#ef4444', 'icon' => 'fa-ban',           'qs' => '?status=2'],
        ['label' => 'Trash',    'key' => 3, 'color' => '#6b7280', 'icon' => 'fa-trash',         'qs' => '?status=3'],
    ];
    foreach ($statCards as $sc):
        $active = $filter === $sc['key'] ? 'cm-stat-active' : '';
    ?>
    <div class="col-6 col-md-3 mb-3 d-flex">
        <a href="<?= route_to('admin.comments') . $sc['qs'] ?>" class="cm-stat w-100 h-100 <?= $active ?>" style="--c:<?= $sc['color'] ?>">
            <i class="fa <?= $sc['icon'] ?> cm-stat-icon"></i>
            <div class="cm-stat-num"><?= $counts[$sc['key']] ?? 0 ?></div>
            <div class="cm-stat-label"><?= $sc['label'] ?></div>
        </a>
    </div>
    <?php endforeach ?>
</div>

<div class="pd-20 card-box mb-30">

    <!-- Search & Filter Bar -->
    <div class="row align-items-center mb-3">
        <div class="col-md-6 mb-2 mb-md-0">
            <form method="get" action="<?= route_to('admin.comments') ?>" class="d-flex align-items-center">
                <?php if ($filter !== null): ?>
                    <input type="hidden" name="status" value="<?= (int) $filter ?>">
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="q" class="form-control form-control-sm" 
                           placeholder="Cari pengirim, email, komentar, atau post..." 
                           value="<?= esc($search ?? '') ?>">
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" type="submit">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <?php if (!empty($search)): ?>
                    <a href="<?= route_to('admin.comments') . ($filter !== null ? '?status=' . $filter : '') ?>" class="btn btn-sm btn-link text-danger ml-2 text-nowrap">
                        <i class="fa fa-times"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-6 text-md-right">
            <?php if ($filter === 3 && ($counts[3] ?? 0) > 0): ?>
            <form method="post" action="<?= route_to('admin.comments.empty-trash') ?>" class="d-inline" onsubmit="return confirm('Kosongkan semua komentar di Kotak Sampah? Tindakan ini tidak dapat dibatalkan.');">
                <?= csrf_field() ?>
                <input type="hidden" name="target" value="trash">
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i> Kosongkan Kotak Sampah
                </button>
            </form>
            <?php elseif ($filter === 2 && ($counts[2] ?? 0) > 0): ?>
            <form method="post" action="<?= route_to('admin.comments.empty-trash') ?>" class="d-inline" onsubmit="return confirm('Hapus permanen semua komentar Spam? Tindakan ini tidak dapat dibatalkan.');">
                <?= csrf_field() ?>
                <input type="hidden" name="target" value="spam">
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-ban"></i> Bersihkan Semua Spam
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Toolbar -->
    <form method="post" action="<?= route_to('admin.comments.bulk') ?>" id="bulk-form">
        <?= csrf_field() ?>
        <div class="cm-toolbar">
            <div class="cm-toolbar-left">
                <label class="cm-check-all-label">
                    <input type="checkbox" id="check-all"> Pilih semua
                </label>
                <select name="action" class="cm-select">
                    <option value="">Aksi bulk...</option>
                    <option value="approve">✓ Setujui</option>
                    <option value="spam">⚑ Tandai Spam</option>
                    <option value="trash">🗑 Pindah Trash</option>
                    <option value="delete">✕ Hapus Permanen</option>
                </select>
                <button type="submit" class="cm-btn cm-btn-secondary"
                        onclick="return confirm('Jalankan aksi pada komentar terpilih?')">Terapkan</button>
            </div>
            <div class="cm-toolbar-right">
                <?php if ($filter !== null || !empty($search)): ?>
                <a href="<?= route_to('admin.comments') ?>" class="cm-btn cm-btn-ghost">
                    <i class="fa fa-times mr-1"></i> Reset Semua Filter
                </a>
                <?php endif ?>
                <span class="cm-total-label font-12 text-muted">
                    <?= count($comments) ?> komentar ditampilkan
                </span>
            </div>
        </div>

        <!-- Tabel -->
        <?php if (empty($comments)): ?>
        <div class="cm-empty">
            <i class="fa fa-comments-o fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada komentar untuk ditampilkan<?= !empty($search) ? ' dengan kata kunci tersebut' : '' ?>.</p>
        </div>
        <?php else: ?>
        <div class="cm-table-wrap">
            <table class="cm-table">
                <thead>
                    <tr>
                        <th width="36"></th>
                        <th width="200">Pengirim</th>
                        <th>Komentar</th>
                        <th width="180">Post</th>
                        <th width="90">Status</th>
                        <th width="90">Waktu</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $c): ?>
                    <?php
                        $statusMap  = [0 => ['Pending','cm-s-pending'], 1 => ['Approved','cm-s-approved'], 2 => ['Spam','cm-s-spam'], 3 => ['Trash','cm-s-trash']];
                        [$sLabel, $sClass] = $statusMap[$c['status']] ?? ['?',''];
                    ?>
                    <tr class="cm-row <?= $c['is_admin'] ? 'cm-row-admin' : '' ?> <?= $c['status'] == 0 ? 'cm-row-pending' : '' ?>" id="comment-row-<?= $c['id'] ?>">
                        <td><input type="checkbox" name="ids[]" value="<?= $c['id'] ?>"></td>
                        <td>
                            <div class="cm-sender">
                                <img src="<?= $c['is_admin'] ? $adminAvatarUrl : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($c['email']))) . '?s=36&d=mp' ?>"
                                     class="cm-avatar" alt="">
                                <div>
                                    <div class="cm-sender-name">
                                        <?= esc($c['name']) ?>
                                        <?php if ($c['is_admin']): ?>
                                            <span class="cm-badge-admin">Admin</span>
                                        <?php endif ?>
                                    </div>
                                    <div class="cm-sender-email"><?= esc($c['email']) ?></div>
                                    <div class="cm-sender-ip"><?= esc($c['ip_address']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($c['parent_id']): ?>
                            <div class="cm-reply-label">
                                <i class="fa fa-reply"></i> Balasan untuk komentar #<?= $c['parent_id'] ?>
                            </div>
                            <?php endif ?>
                            <div class="cm-comment-text"><?= esc(mb_substr($c['comment'], 0, 150)) ?><?= mb_strlen($c['comment']) > 150 ? '…' : '' ?></div>

                            <!-- Form balas inline -->
                            <div class="cm-reply-box" id="rb-<?= $c['id'] ?>" style="display:none;">
                                <textarea class="cm-reply-textarea" rows="2" placeholder="Tulis balasan admin..."></textarea>
                                <div class="cm-reply-actions">
                                    <button type="button" class="cm-btn cm-btn-primary cm-btn-sm btn-send-reply"
                                            data-id="<?= $c['id'] ?>" data-post="<?= $c['post_id'] ?>">Kirim</button>
                                    <button type="button" class="cm-btn cm-btn-ghost cm-btn-sm btn-cancel-reply"
                                            data-target="rb-<?= $c['id'] ?>">Batal</button>
                                    <span class="cm-reply-msg"></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="<?= base_url('post/' . $c['post_slug']) ?>" target="_blank" class="cm-post-link">
                                <?= esc(mb_substr($c['post_title'], 0, 45)) ?><?= mb_strlen($c['post_title']) > 45 ? '…' : '' ?>
                                <i class="fa fa-external-link fa-xs ml-1"></i>
                            </a>
                        </td>
                        <td><span class="cm-status <?= $sClass ?>" id="status-badge-<?= $c['id'] ?>"><?= $sLabel ?></span></td>
                        <td class="cm-date"><?= date('d/m/y\nH:i', strtotime($c['created_at'])) ?></td>
                        <td>
                            <div class="cm-actions">
                                <?php if ($c['status'] != 1): ?>
                                <button type="button" class="cm-action-btn cm-action-approve btn-ajax-action" 
                                        data-url="<?= route_to('admin.comments.approve', $c['id']) ?>"
                                        data-row="<?= $c['id'] ?>"
                                        data-action="approve" title="Setujui">
                                    <i class="fa fa-check"></i>
                                </button>
                                <?php endif ?>
                                <button type="button" class="cm-action-btn cm-action-reply btn-toggle-reply"
                                        data-target="rb-<?= $c['id'] ?>" title="Balas">
                                    <i class="fa fa-reply"></i>
                                </button>
                                <?php if ($c['status'] != 2): ?>
                                <button type="button" class="cm-action-btn cm-action-spam btn-ajax-action" 
                                        data-url="<?= route_to('admin.comments.spam', $c['id']) ?>"
                                        data-row="<?= $c['id'] ?>"
                                        data-action="spam" title="Spam">
                                    <i class="fa fa-ban"></i>
                                </button>
                                <?php endif ?>
                                <?php if ($c['status'] != 3): ?>
                                <button type="button" class="cm-action-btn cm-action-trash btn-ajax-action" 
                                        data-url="<?= route_to('admin.comments.trash', $c['id']) ?>"
                                        data-row="<?= $c['id'] ?>"
                                        data-action="trash" title="Pindah ke Trash" style="color:#6b7280;">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                                <?php else: ?>
                                <button type="button" class="cm-action-btn cm-action-delete btn-ajax-action" 
                                        data-url="<?= route_to('admin.comments.destroy', $c['id']) ?>"
                                        data-row="<?= $c['id'] ?>"
                                        data-action="destroy"
                                        data-confirm="Hapus permanen komentar ini?" title="Hapus Permanen">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <?php endif ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php endif ?>
    </form>

    <!-- Pager -->
    <?php if (isset($pager)): ?>
    <div class="mt-3"><?= $pager->links() ?></div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
/* ── Stat cards ─────────────────────────────────────────── */
.cm-stat {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-top: 3px solid var(--c);
    border-radius: 10px;
    padding: 1.1rem .75rem;
    text-decoration: none;
    color: inherit;
    box-sizing: border-box;
    min-height: 110px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.cm-stat:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
    transform: translateY(-2px);
    text-decoration: none;
    color: inherit;
}
.cm-stat-active {
    box-shadow: 0 4px 16px rgba(0, 0, 0, .1);
    border: 1.5px solid var(--c);
    border-top: 3px solid var(--c);
    background: #fafbff;
}
.cm-stat-icon { font-size: 1.35rem; color: var(--c); margin-bottom: 6px; height: 22px; display: flex; align-items: center; justify-content: center; }
.cm-stat-num  { font-size: 1.6rem; font-weight: 800; color: #111; line-height: 1; }
.cm-stat-label{ font-size: .8rem; color: #6b7280; margin-top: 4px; font-weight: 500; }

/* ── Toolbar ────────────────────────────────────────────── */
.cm-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 8px; margin-bottom: 1rem;
    padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;
}
.cm-toolbar-left, .cm-toolbar-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.cm-check-all-label { display: flex; align-items: center; gap: 6px; font-size: .85rem; color: #374151; cursor: pointer; }
.cm-select {
    border: 1px solid #d1d5db; border-radius: 6px; padding: 5px 10px;
    font-size: .82rem; color: #374151; background: #fff; cursor: pointer;
}
.cm-total-label { font-size: .8rem; color: #9ca3af; }

/* ── Buttons ────────────────────────────────────────────── */
.cm-btn {
    display: inline-flex; align-items: center; gap: 4px;
    border: none; border-radius: 6px; padding: 6px 12px;
    font-size: .82rem; font-weight: 500; cursor: pointer; text-decoration: none;
    transition: opacity .15s;
}
.cm-btn:hover { opacity: .85; text-decoration: none; }
.cm-btn-primary   { background: #2563eb; color: #fff; }
.cm-btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
.cm-btn-ghost     { background: none; color: #6b7280; border: 1px solid #e5e7eb; }
.cm-btn-sm        { padding: 4px 10px; font-size: .78rem; }

/* ── Table ──────────────────────────────────────────────── */
.cm-table-wrap { overflow-x: auto; }
.cm-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.cm-table thead th {
    background: #f9fafb; border-bottom: 1px solid #e5e7eb;
    padding: 10px 12px; font-weight: 600; color: #374151;
    text-align: left; white-space: nowrap;
}
.cm-table tbody td { padding: 12px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
.cm-row:hover td { background: #fafafa; }
.cm-row-admin td { background: #eff6ff; }
.cm-row-pending td { background: #fffbeb; }

/* Sender */
.cm-sender { display: flex; gap: 10px; align-items: flex-start; }
.cm-avatar { width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0; border: 1px solid #e5e7eb; }
.cm-sender-name  { font-weight: 600; color: #111; font-size: .85rem; display: flex; align-items: center; gap: 5px; }
.cm-sender-email { font-size: .75rem; color: #6b7280; }
.cm-sender-ip    { font-size: .72rem; color: #9ca3af; }
.cm-badge-admin  {
    background: #2563eb; color: #fff; font-size: .6rem; font-weight: 700;
    padding: 1px 6px; border-radius: 20px; text-transform: uppercase; letter-spacing: .04em;
}

/* Comment text */
.cm-reply-label { font-size: .75rem; color: #9ca3af; margin-bottom: 4px; }
.cm-comment-text { color: #374151; line-height: 1.55; }

/* Post link */
.cm-post-link { font-size: .8rem; color: #2563eb; text-decoration: none; }
.cm-post-link:hover { text-decoration: underline; }

/* Status badges */
.cm-status {
    display: inline-block; padding: 3px 9px; border-radius: 20px;
    font-size: .72rem; font-weight: 600; white-space: nowrap;
}
.cm-s-pending  { background: #fef3c7; color: #92400e; }
.cm-s-approved { background: #d1fae5; color: #065f46; }
.cm-s-spam     { background: #fee2e2; color: #991b1b; }
.cm-s-trash    { background: #f3f4f6; color: #6b7280; }

/* Date */
.cm-date { font-size: .78rem; color: #6b7280; white-space: pre-line; }

/* Action buttons */
.cm-actions { display: flex; gap: 4px; flex-wrap: wrap; }
.cm-action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 6px; border: none;
    font-size: .8rem; cursor: pointer; text-decoration: none;
    transition: opacity .15s;
}
.cm-action-btn:hover { opacity: .75; }
.cm-action-approve { background: #d1fae5; color: #065f46; }
.cm-action-reply   { background: #dbeafe; color: #1e40af; }
.cm-action-spam    { background: #fef3c7; color: #92400e; }
.cm-action-delete  { background: #fee2e2; color: #991b1b; }

/* Reply box */
.cm-reply-box { margin-top: 10px; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 10px; }
.cm-reply-textarea {
    width: 100%; border: 1px solid #d1d5db; border-radius: 6px;
    padding: 7px 10px; font-size: .82rem; resize: vertical; font-family: inherit;
}
.cm-reply-actions { display: flex; align-items: center; gap: 6px; margin-top: 6px; }
.cm-reply-msg { font-size: .78rem; color: #6b7280; }

/* Empty */
.cm-empty { text-align: center; padding: 3rem 1rem; }
</style>

<script>
// Check all
document.getElementById('check-all').addEventListener('change', function () {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
});

// Toggle reply box
document.addEventListener('click', function (e) {
    const toggleBtn = e.target.closest('.btn-toggle-reply');
    if (toggleBtn) {
        const el = document.getElementById(toggleBtn.dataset.target);
        if (!el) return;
        const isOpen = el.style.display !== 'none';
        document.querySelectorAll('.cm-reply-box').forEach(b => b.style.display = 'none');
        el.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) el.querySelector('textarea')?.focus();
    }

    const cancelBtn = e.target.closest('.btn-cancel-reply');
    if (cancelBtn) {
        const el = document.getElementById(cancelBtn.dataset.target);
        if (el) el.style.display = 'none';
    }
});

// Kirim balasan admin
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-send-reply');
    if (!btn) return;

    const box   = document.getElementById('rb-' + btn.dataset.id);
    const text  = box.querySelector('.cm-reply-textarea').value.trim();
    const msgEl = box.querySelector('.cm-reply-msg');

    if (!text) { msgEl.textContent = 'Balasan tidak boleh kosong.'; return; }

    const fd = new FormData();
    fd.append('parent_id', btn.dataset.id);
    fd.append('post_id',   btn.dataset.post);
    fd.append('comment',   text);
    fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    btn.disabled = true;
    btn.textContent = 'Mengirim...';

    fetch('<?= route_to('admin.comments.reply') ?>', {
        method: 'POST', body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        msgEl.textContent = res.msg;
        if (res.status) {
            box.querySelector('.cm-reply-textarea').value = '';
            setTimeout(() => location.reload(), 1200);
        }
    })
    .finally(() => { btn.disabled = false; btn.textContent = 'Kirim'; });
});

// Single Action AJAX Handler (Approve, Spam, Trash, Destroy)
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-ajax-action');
    if (!btn) return;

    const url     = btn.dataset.url;
    const rowId   = btn.dataset.row;
    const action  = btn.dataset.action;
    const confirm = btn.dataset.confirm;

    if (confirm && !window.confirm(confirm)) {
        return;
    }

    btn.disabled = true;

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 1) {
            if (typeof toastr !== 'undefined') {
                toastr.success(res.msg);
            }

            const row = document.getElementById('comment-row-' + rowId);
            if (action === 'destroy' || action === 'trash') {
                if (row) {
                    row.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => row.remove(), 400);
                }
            } else if (action === 'approve') {
                const badge = document.getElementById('status-badge-' + rowId);
                if (badge) {
                    badge.className = 'cm-status cm-s-approved';
                    badge.textContent = 'Approved';
                }
                btn.remove();
            } else if (action === 'spam') {
                const badge = document.getElementById('status-badge-' + rowId);
                if (badge) {
                    badge.className = 'cm-status cm-s-spam';
                    badge.textContent = 'Spam';
                }
                btn.remove();
            }
            setTimeout(() => location.reload(), 1000);
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error(res.msg || 'Gagal memproses aksi.');
            } else {
                alert(res.msg || 'Gagal memproses.');
            }
            btn.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        location.href = url; // Fallback ke normal redirect jika ada network error
    });
});
</script>
<?= $this->endSection() ?>
