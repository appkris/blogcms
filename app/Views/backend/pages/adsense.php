<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12">
            <div class="title"><h4>Pengaturan Google AdSense</h4></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Beranda</a></li>
                    <li class="breadcrumb-item active">AdSense</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fa fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif ?>

<!-- Info kebijakan -->
<div class="alert alert-info mb-4">
    <strong><i class="fa fa-info-circle mr-1"></i> Kebijakan Google AdSense:</strong>
    <ul class="mb-0 mt-1 pl-3">
        <li>Script <code>adsbygoogle.js</code> hanya dimuat <strong>sekali</strong> di <code>&lt;head&gt;</code> — sudah ditangani otomatis.</li>
        <li>Maksimal <strong>3 unit iklan</strong> per halaman sesuai kebijakan AdSense.</li>
        <li>Iklan tidak boleh ditempatkan di halaman tanpa konten atau halaman error.</li>
        <li>Pastikan situs sudah <strong>disetujui</strong> AdSense sebelum mengaktifkan.</li>
    </ul>
</div>

<form action="<?= route_to('admin.adsense.update') ?>" method="post">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Konfigurasi utama -->
        <div class="col-md-6">
            <div class="pd-20 card-box mb-4">
                <h5 class="mb-3"><i class="fa fa-cog mr-1"></i> Konfigurasi Utama</h5>

                <div class="form-group">
                    <label><b>Publisher ID</b> <small class="text-muted">(ca-pub-XXXXXXXXXXXXXXXX)</small></label>
                    <input type="text" name="publisher_id" class="form-control"
                           value="<?= esc($ads->publisher_id ?? '') ?>"
                           placeholder="ca-pub-1234567890123456">
                    <small class="text-muted">Temukan di dashboard AdSense → Akun → Informasi akun.</small>
                </div>

                <div class="form-group">
                    <label class="d-block"><b>Status AdSense</b></label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active"
                               name="is_active" value="1" <?= $ads->is_active ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is_active">
                            <?= $ads->is_active ? '<span class="text-success">Aktif</span>' : '<span class="text-muted">Nonaktif</span>' ?>
                        </label>
                    </div>
                    <small class="text-muted">Matikan untuk menonaktifkan semua iklan sekaligus.</small>
                </div>
            </div>
        </div>

        <!-- Status saat ini -->
        <div class="col-md-6">
            <div class="pd-20 card-box mb-4">
                <h5 class="mb-3"><i class="fa fa-bar-chart mr-1"></i> Status Iklan</h5>
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr><th>Posisi</th><th>Slot ID</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Header</td>
                            <td><code><?= esc($ads->slot_header ?: '-') ?></code></td>
                            <td><?= $ads->show_header ? '<span class="badge badge-success">Tampil</span>' : '<span class="badge badge-secondary">Sembunyikan</span>' ?></td>
                        </tr>
                        <tr>
                            <td>Sidebar</td>
                            <td><code><?= esc($ads->slot_sidebar ?: '-') ?></code></td>
                            <td><?= $ads->show_sidebar ? '<span class="badge badge-success">Tampil</span>' : '<span class="badge badge-secondary">Sembunyikan</span>' ?></td>
                        </tr>
                        <tr>
                            <td>Konten</td>
                            <td><code><?= esc($ads->slot_content ?: '-') ?></code></td>
                            <td><?= $ads->show_content ? '<span class="badge badge-success">Tampil</span>' : '<span class="badge badge-secondary">Sembunyikan</span>' ?></td>
                        </tr>
                    </tbody>
                </table>
                <small class="text-muted">
                    <i class="fa fa-exclamation-triangle text-warning"></i>
                    Maksimal 3 posisi iklan aktif sesuai kebijakan AdSense.
                </small>
            </div>
        </div>
    </div>

    <!-- Slot per posisi -->
    <div class="pd-20 card-box mb-4">
        <h5 class="mb-3"><i class="fa fa-th-large mr-1"></i> Pengaturan Slot Iklan per Posisi</h5>
        <p class="text-muted small">Slot ID ditemukan di dashboard AdSense → Iklan → Berdasarkan unit iklan.</p>

        <div class="row">
            <!-- Header -->
            <div class="col-md-4">
                <div class="card border mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <strong>Header</strong>
                        <div class="custom-control custom-switch mb-0">
                            <input type="checkbox" class="custom-control-input" id="show_header"
                                   name="show_header" value="1" <?= $ads->show_header ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="show_header">Tampilkan</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="small">Ad Slot ID</label>
                            <input type="text" name="slot_header" class="form-control form-control-sm"
                                   value="<?= esc($ads->slot_header ?? '') ?>"
                                   placeholder="1234567890">
                        </div>
                        <small class="text-muted">Posisi: di bawah navbar, sebelum konten.</small>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card border mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <strong>Sidebar</strong>
                        <div class="custom-control custom-switch mb-0">
                            <input type="checkbox" class="custom-control-input" id="show_sidebar"
                                   name="show_sidebar" value="1" <?= $ads->show_sidebar ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="show_sidebar">Tampilkan</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="small">Ad Slot ID</label>
                            <input type="text" name="slot_sidebar" class="form-control form-control-sm"
                                   value="<?= esc($ads->slot_sidebar ?? '') ?>"
                                   placeholder="1234567890">
                        </div>
                        <small class="text-muted">Posisi: di sidebar kanan halaman post.</small>
                    </div>
                </div>
            </div>

            <!-- Konten -->
            <div class="col-md-4">
                <div class="card border mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <strong>Dalam Konten</strong>
                        <div class="custom-control custom-switch mb-0">
                            <input type="checkbox" class="custom-control-input" id="show_content"
                                   name="show_content" value="1" <?= $ads->show_content ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="show_content">Tampilkan</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="small">Ad Slot ID</label>
                            <input type="text" name="slot_content" class="form-control form-control-sm"
                                   value="<?= esc($ads->slot_content ?? '') ?>"
                                   placeholder="1234567890">
                        </div>
                        <small class="text-muted">Posisi: setelah isi artikel.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save mr-1"></i> Simpan Pengaturan
        </button>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Update label toggle status aktif secara real-time
document.getElementById('is_active').addEventListener('change', function () {
    this.nextElementSibling.innerHTML = this.checked
        ? '<span class="text-success">Aktif</span>'
        : '<span class="text-muted">Nonaktif</span>';
});
</script>
<?= $this->endSection() ?>
