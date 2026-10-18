<?php
/**
 * Partial: comments.php
 * Variabel: $post, $comments (nested 1 level), $totalComments
 */
$adminAvatarUrl   = get_admin_avatar_url(44);
$adminAvatarSmUrl = get_admin_avatar_url(36);
$recaptchaSiteKey = env('RECAPTCHA_SITE_KEY');
?>

<div class="comments-section" id="comments">

    <!-- Header -->
    <div class="cs-header">
        <h3 class="cs-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <?= $totalComments ?> Komentar
        </h3>
    </div>

    <!-- Daftar komentar -->
    <div class="cs-list">
        <?php if (empty($comments)): ?>
            <div class="cs-empty">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p>Belum ada komentar. Jadilah yang pertama!</p>
            </div>
        <?php else: ?>
            <?php foreach ($comments as $c): ?>

            <!-- Root comment -->
            <div class="cs-comment" id="comment-<?= $c->id ?>">
                <div class="cs-avatar">
                    <img src="<?= $c->is_admin ? $adminAvatarUrl : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($c->email))) . '?s=44&d=mp' ?>"
                         alt="<?= esc($c->name) ?>" width="44" height="44">
                </div>
                <div class="cs-body">
                    <div class="cs-meta">
                        <span class="cs-name"><?= esc($c->name) ?></span>
                        <?php if ($c->is_admin): ?>
                            <span class="cs-badge-admin">Admin</span>
                        <?php endif ?>
                        <span class="cs-date"><?= date('d M Y', strtotime($c->created_at)) ?></span>
                    </div>
                    <div class="cs-text"><?= nl2br(esc($c->comment)) ?></div>
                    <button class="cs-btn-reply" data-target="rf-<?= $c->id ?>"
                            data-post="<?= $c->post_id ?>" data-parent="<?= $c->id ?>">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg>
                        Balas
                    </button>

                    <!-- Form reply (tersembunyi) -->
                    <div class="cs-reply-form" id="rf-<?= $c->id ?>" style="display:none;">
                        <form class="cs-form" data-post-id="<?= $c->post_id ?>" data-parent-id="<?= $c->id ?>">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf">
                            <div class="cs-form-row">
                                <input type="text"  name="name"    placeholder="Nama *"  required>
                                <input type="email" name="email"   placeholder="Email *" required>
                            </div>
                            <textarea name="comment" placeholder="Tulis balasan..." required minlength="5" maxlength="1000" rows="3"></textarea>
                            <?php if (!empty($recaptchaSiteKey)): ?>
                                <div class="cs-recaptcha-wrapper my-2">
                                    <div class="cs-reply-recaptcha" data-sitekey="<?= esc($recaptchaSiteKey) ?>"></div>
                                </div>
                            <?php endif; ?>
                            <div class="cs-form-actions">
                                <button type="submit" class="cs-btn-submit">Kirim Balasan</button>
                                <button type="button" class="cs-btn-cancel" data-target="rf-<?= $c->id ?>">Batal</button>
                            </div>
                            <div class="cs-msg"></div>
                        </form>
                    </div>

                    <!-- Replies -->
                    <?php if (!empty($c->replies)): ?>
                    <div class="cs-replies">
                        <?php foreach ($c->replies as $r): ?>
                        <div class="cs-comment cs-reply" id="comment-<?= $r->id ?>">
                            <div class="cs-avatar cs-avatar-sm">
                                <img src="<?= $r->is_admin ? $adminAvatarSmUrl : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($r->email))) . '?s=36&d=mp' ?>"
                                     alt="<?= esc($r->name) ?>" width="36" height="36">
                            </div>
                            <div class="cs-body">
                                <div class="cs-meta">
                                    <span class="cs-name"><?= esc($r->name) ?></span>
                                    <?php if ($r->is_admin): ?>
                                        <span class="cs-badge-admin">Admin</span>
                                    <?php endif ?>
                                    <span class="cs-date"><?= date('d M Y', strtotime($r->created_at)) ?></span>
                                </div>
                                <div class="cs-text"><?= nl2br(esc($r->comment)) ?></div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                    <?php endif ?>

                </div>
            </div>

            <?php endforeach ?>
        <?php endif ?>
    </div>

    <!-- Form komentar baru -->
    <div class="cs-new-comment">
        <h4 class="cs-form-title">Tinggalkan Komentar</h4>
        <p class="cs-form-note">Komentar akan ditampilkan setelah disetujui. Link tidak diperbolehkan.</p>
        <form class="cs-form" id="main-comment-form" data-post-id="<?= $post->id ?>" data-parent-id="0">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf">
            <div class="cs-form-row">
                <input type="text"  name="name"  placeholder="Nama *"  required>
                <input type="email" name="email" placeholder="Email *" required>
            </div>
            <textarea name="comment" placeholder="Tulis komentar Anda..." required minlength="5" maxlength="1000" rows="5"></textarea>
            <?php if (!empty($recaptchaSiteKey)): ?>
                <div class="cs-recaptcha-wrapper my-3">
                    <div class="g-recaptcha" data-sitekey="<?= esc($recaptchaSiteKey) ?>"></div>
                </div>
            <?php endif; ?>
            <div class="cs-form-actions">
                <button type="submit" class="cs-btn-submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Kirim Komentar
                </button>
            </div>
            <div class="cs-msg"></div>
        </form>
    </div>

</div><!-- /comments-section -->

<style>
/* ── Comments Section ─────────────────────────────────────── */
.comments-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
    font-family: inherit;
}

/* Header */
.cs-header { margin-bottom: 1.5rem; }
.cs-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.15rem;
    font-weight: 700;
    color: #111;
    margin: 0;
}

/* Empty state */
.cs-empty {
    text-align: center;
    padding: 2.5rem 1rem;
    color: #9ca3af;
}
.cs-empty p { margin-top: .75rem; font-size: .9rem; }

/* Comment item */
.cs-comment {
    display: flex;
    gap: 12px;
    margin-bottom: 1.5rem;
}
.cs-avatar img {
    width: 44px; height: 44px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #f3f4f6;
}
.cs-avatar-sm img { width: 36px; height: 36px; }

.cs-body { flex: 1; min-width: 0; }

.cs-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 5px;
}
.cs-name {
    font-weight: 600;
    font-size: .9rem;
    color: #111;
}
.cs-badge-admin {
    display: inline-flex;
    align-items: center;
    background: #2563eb;
    color: #fff;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .04em;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
}
.cs-date {
    font-size: .78rem;
    color: #9ca3af;
}
.cs-text {
    font-size: .9rem;
    line-height: 1.65;
    color: #374151;
    word-break: break-word;
}

/* Reply button */
.cs-btn-reply {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: none;
    border: none;
    color: #6b7280;
    font-size: .78rem;
    font-weight: 500;
    padding: 4px 0;
    margin-top: 6px;
    cursor: pointer;
    transition: color .2s;
}
.cs-btn-reply:hover { color: #2563eb; }

/* Replies container */
.cs-replies {
    margin-top: 1rem;
    padding-left: 1rem;
    border-left: 2px solid #e5e7eb;
}
.cs-reply { margin-bottom: 1rem; }
.cs-reply:last-child { margin-bottom: 0; }

/* Admin reply highlight */
.cs-reply .cs-body {
    background: #eff6ff;
    border-radius: 8px;
    padding: 10px 12px;
}

/* Reply form */
.cs-reply-form {
    margin-top: 10px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 14px;
}

/* New comment box */
.cs-new-comment {
    margin-top: 2.5rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 1.5rem;
}
.cs-form-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111;
    margin: 0 0 4px;
}
.cs-form-note {
    font-size: .78rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

/* Form elements */
.cs-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 10px;
}
@media (max-width: 576px) {
    .cs-form-row { grid-template-columns: 1fr; }
}
.cs-form input,
.cs-form textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: .875rem;
    color: #111;
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    font-family: inherit;
}
.cs-form input:focus,
.cs-form textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
.cs-form textarea { resize: vertical; margin-bottom: 10px; }

.cs-form-actions { display: flex; gap: 8px; align-items: center; }

.cs-btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 9px 18px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
}
.cs-btn-submit:hover { background: #1d4ed8; }
.cs-btn-submit:disabled { opacity: .6; cursor: not-allowed; }

.cs-btn-cancel {
    background: none;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 14px;
    font-size: .875rem;
    color: #6b7280;
    cursor: pointer;
    transition: border-color .2s;
}
.cs-btn-cancel:hover { border-color: #9ca3af; }

/* Alert messages */
.cs-msg .cs-alert {
    margin-top: 8px;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: .82rem;
}
.cs-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.cs-alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
</style>

<script>
(function () {
    const STORE_URL = '<?= route_to('comment.store') ?>';
    const RECAPTCHA_SITE_KEY = '<?= esc($recaptchaSiteKey) ?>';
    const widgetMap = new WeakMap();

    // Inisialisasi Google reCAPTCHA script jika key tersedia
    if (RECAPTCHA_SITE_KEY && !window.grecaptcha) {
        const s = document.createElement('script');
        s.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
        s.async = true;
        s.defer = true;
        s.onload = function () {
            if (window.grecaptcha && window.grecaptcha.ready) {
                window.grecaptcha.ready(initRecaptcha);
            }
        };
        document.head.appendChild(s);
    } else if (RECAPTCHA_SITE_KEY && window.grecaptcha && window.grecaptcha.ready) {
        window.grecaptcha.ready(initRecaptcha);
    }

    function initRecaptcha() {
        const mainEl = document.querySelector('#main-comment-form .g-recaptcha');
        if (mainEl && !widgetMap.has(mainEl)) {
            try {
                const wId = grecaptcha.render(mainEl, { sitekey: RECAPTCHA_SITE_KEY });
                widgetMap.set(mainEl.closest('form'), wId);
            } catch (err) {}
        }
    }

    // Toggle form reply
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.cs-btn-reply');
        if (btn) {
            const el = document.getElementById(btn.dataset.target);
            if (!el) return;
            const isOpen = el.style.display !== 'none';
            // Tutup semua form reply lain
            document.querySelectorAll('.cs-reply-form').forEach(f => f.style.display = 'none');
            el.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) {
                el.querySelector('input[name="name"]')?.focus();
                // Render reCAPTCHA di form reply jika belum di-render
                if (RECAPTCHA_SITE_KEY && window.grecaptcha) {
                    const replyBox = el.querySelector('.cs-reply-recaptcha');
                    const replyForm = el.querySelector('form');
                    if (replyBox && replyForm && !widgetMap.has(replyForm)) {
                        try {
                            const wId = grecaptcha.render(replyBox, { sitekey: RECAPTCHA_SITE_KEY });
                            widgetMap.set(replyForm, wId);
                        } catch (err) {}
                    }
                }
            }
        }

        const cancel = e.target.closest('.cs-btn-cancel');
        if (cancel) {
            const el = document.getElementById(cancel.dataset.target);
            if (el) el.style.display = 'none';
        }
    });

    // Submit form
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('.cs-form');
        if (!form) return;
        e.preventDefault();

        const msgEl    = form.querySelector('.cs-msg');
        const btn      = form.querySelector('.cs-btn-submit');
        const origText = btn.innerHTML;
        const postId   = form.dataset.postId;
        const parentId = form.dataset.parentId;

        const fd = new FormData(form);
        fd.set('post_id',   postId);
        fd.set('parent_id', parentId || '0');

        const csrf = form.querySelector('.ci_csrf');
        if (csrf) fd.set(csrf.name, csrf.value);

        // Ambil token response reCAPTCHA
        let recaptchaToken = '';
        if (window.grecaptcha) {
            if (widgetMap.has(form)) {
                recaptchaToken = grecaptcha.getResponse(widgetMap.get(form));
            } else {
                recaptchaToken = grecaptcha.getResponse();
            }
        }
        if (recaptchaToken) {
            fd.set('g-recaptcha-response', recaptchaToken);
        }

        btn.disabled = true;
        btn.textContent = 'Mengirim...';
        msgEl.innerHTML = '';

        fetch(STORE_URL, {
            method: 'POST', body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 1) {
                msgEl.innerHTML = '<div class="cs-alert cs-alert-success">' + res.msg + '</div>';
                form.reset();
                if (window.grecaptcha) {
                    if (widgetMap.has(form)) {
                        grecaptcha.reset(widgetMap.get(form));
                    } else {
                        grecaptcha.reset();
                    }
                }
                const wrapper = form.closest('.cs-reply-form');
                if (wrapper) setTimeout(() => wrapper.style.display = 'none', 2200);
            } else {
                let msg = res.msg || '';
                if (res.errors) msg = Object.values(res.errors).join('<br>');
                msgEl.innerHTML = '<div class="cs-alert cs-alert-error">' + msg + '</div>';
                // Reset reCAPTCHA agar user bisa coba lagi
                if (window.grecaptcha) {
                    if (widgetMap.has(form)) {
                        grecaptcha.reset(widgetMap.get(form));
                    } else {
                        grecaptcha.reset();
                    }
                }
            }
        })
        .catch(() => {
            msgEl.innerHTML = '<div class="cs-alert cs-alert-error">Terjadi kesalahan. Coba lagi.</div>';
            if (window.grecaptcha) {
                if (widgetMap.has(form)) {
                    grecaptcha.reset(widgetMap.get(form));
                } else {
                    grecaptcha.reset();
                }
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = origText;
        });
    });
})();
</script>
