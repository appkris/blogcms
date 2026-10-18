<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="title"><h4>Halaman Statis</h4></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Beranda</a></li>
                    <li class="breadcrumb-item active">Halaman Statis</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?= route_to('admin.pages.add') ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Halaman
            </a>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif ?>
<?php if (session()->getFlashdata('fail')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('fail') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif ?>

<div class="pd-20 card-box mb-30">
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Tampil di Footer</th>
                <th>Urutan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pages)): ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada halaman.</td></tr>
            <?php else: ?>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td><?= $p->id ?></td>
                    <td><?= esc($p->title) ?></td>
                    <td><code><?= esc($p->slug) ?></code></td>
                    <td>
                        <?php if ($p->status): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Draft</span>
                        <?php endif ?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-<?= $p->show_in_footer ? 'success' : 'outline-secondary' ?> toggle-footer-btn"
                            data-id="<?= $p->id ?>"
                            data-route="<?= route_to('admin.pages.toggle-footer', $p->id) ?>"
                            title="<?= $p->show_in_footer ? 'Tampil di footer' : 'Tidak tampil di footer' ?>">
                            <i class="fa fa-<?= $p->show_in_footer ? 'check' : 'times' ?>"></i>
                        </button>
                    </td>
                    <td><?= $p->ordering ?></td>
                    <td>
                        <a href="<?= route_to('admin.pages.edit', $p->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= base_url('page/' . $p->slug) ?>" target="_blank" class="btn btn-sm btn-info">View</a>
                        <a href="<?= route_to('admin.pages.delete', $p->id) ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hapus halaman ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).on('click', '.toggle-footer-btn', function () {
    const btn = $(this);
    $.post(btn.data('route'), {<?= csrf_token() ?>: '<?= csrf_hash() ?>'}, function (res) {
        if (res.status) {
            const on = res.show_in_footer;
            btn.toggleClass('btn-success', on).toggleClass('btn-outline-secondary', !on);
            btn.find('i').toggleClass('fa-check', on).toggleClass('fa-times', !on);
            btn.attr('title', on ? 'Tampil di footer' : 'Tidak tampil di footer');
        }
    });
});
</script>
<?= $this->endSection() ?>
