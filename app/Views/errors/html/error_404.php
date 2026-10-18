<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>404 - Halaman Tidak Ditemukan | <?= isset($settings) ? esc($settings->blog_title) : 'Blog' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="/frontend/plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/frontend/css/style.css">
    <style>
        body { background: #f8f9fa; }
        .error-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: #dee2e6;
            line-height: 1;
        }
        .error-title { font-size: 1.75rem; font-weight: 700; }
        .error-icon { font-size: 3rem; color: #6c757d; }
    </style>
</head>
<body>
<div class="error-wrapper">
    <div class="text-center px-3">
        <div class="error-icon mb-3"><i class="fas fa-map-signs"></i></div>
        <div class="error-code">404</div>
        <h1 class="error-title mt-2 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-muted mb-4">
            Maaf, halaman yang kamu cari tidak ada atau sudah dipindahkan.<br>
            <?php if (ENVIRONMENT !== 'production' && isset($message)): ?>
                <small class="text-danger"><?= esc($message) ?></small>
            <?php endif ?>
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap" style="gap:10px;">
            <a href="/" class="btn btn-primary px-4">
                <i class="fas fa-home mr-1"></i> Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left mr-1"></i> Halaman Sebelumnya
            </a>
        </div>
    </div>
</div>
<script src="/frontend/plugins/jquery/jquery.min.js"></script>
<script src="/frontend/plugins/bootstrap/bootstrap.min.js"></script>
</body>
</html>
