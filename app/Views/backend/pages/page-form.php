<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12">
            <div class="title"><h4><?= esc($pageTitle) ?></h4></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.pages') ?>">Halaman Statis</a></li>
                    <li class="breadcrumb-item active"><?= esc($pageTitle) ?></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <?php
        $isEdit  = $page !== null;
        $action  = $isEdit ? route_to('admin.pages.update', $page->id) : route_to('admin.pages.create');
    ?>
    <form action="<?= $action ?>" method="post">
        <?= csrf_field() ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?php foreach ($validation->getErrors() as $err): ?>
                    <div><?= esc($err) ?></div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <div class="form-group">
            <label>Judul Halaman <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                   value="<?= esc($isEdit ? $page->title : old('title')) ?>"
                   placeholder="Contoh: Privacy Policy" required>
        </div>

        <div class="form-group">
            <label>Konten <span class="text-danger">*</span></label>
            <textarea name="content" id="page-content" class="form-control" rows="12"><?= $isEdit ? $page->content : old('content') ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= ($isEdit && $page->status == 1) || !$isEdit ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= $isEdit && $page->status == 0 ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Urutan</label>
                    <input type="number" name="ordering" class="form-control"
                           value="<?= esc($isEdit ? $page->ordering : 100) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="d-block">Tampilkan di Footer Navigasi</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" class="custom-control-input" id="show_in_footer"
                               name="show_in_footer" value="1"
                               <?= ($isEdit && $page->show_in_footer) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="show_in_footer">
                            Tampilkan di navigasi footer
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> <?= $isEdit ? 'Perbarui' : 'Simpan' ?>
            </button>
            <a href="<?= route_to('admin.pages') ?>" class="btn btn-secondary ml-2">Batal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="/extra-assets/summernote/summernote-bs4.min.css">
<script src="/extra-assets/summernote/summernote-bs4.min.js"></script>
<script>
$('#page-content').summernote({
    height: 350,
    toolbar: [
        ['style', ['bold','italic','underline','clear']],
        ['font', ['strikethrough']],
        ['para', ['ul','ol','paragraph']],
        ['insert', ['link','hr']],
        ['view', ['fullscreen','codeview']]
    ]
});
</script>
<?= $this->endSection() ?>
