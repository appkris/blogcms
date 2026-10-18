<?php use App\Libraries\CIAuth; ?>
<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title"><h4>Tambah Artikel</h4></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Beranda</a></li>
                    <li class="breadcrumb-item active">Tambah Artikel</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a class="btn btn-primary" href="<?= route_to('all-posts') ?>">Lihat Artikel</a>
        </div>
    </div>
</div>

<!-- Alert area -->
<div id="form-alert" style="display:none;"></div>

<form action="<?= route_to('create-post') ?>" method="post" enctype="multipart/form-data"
      autocomplete="off" id="addPostForm">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_field">

    <div class="row">
        <div class="col-md-9">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Judul Artikel</b></label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul artikel">
                        <span class="text-danger small title_error"></span>
                    </div>
                    <div class="form-group">
                        <label><b>Konten</b></label>
                        <textarea name="content" id="content" class="form-control" rows="10"
                                  placeholder="Tulis konten artikel..."></textarea>
                        <span class="text-danger small content_error"></span>
                    </div>
                </div>
            </div>

            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Meta Keyword</b> <small>(Pisahkan dengan koma)</small></label>
                        <input type="text" name="meta_keywords" class="form-control" placeholder="Masukkan meta keyword">
                    </div>
                    <div class="form-group">
                        <label><b>Meta Deskripsi</b></label>
                        <textarea name="meta_description" class="form-control" rows="4"
                                  placeholder="Tulis meta deskripsi..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Kategori Artikel</b></label>
                        <select name="category" class="custom-select form-control">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="text-danger small category_error"></span>
                    </div>

                    <div class="form-group">
                        <div class="mb-2" style="max-width:100px;">
                            <img src="" alt="" class="img-thumbnail" id="post-featured-image-preview"
                                 style="display:none;">
                        </div>
                        <label><b>Gambar Utama</b></label>
                        <input type="file" name="featured_image" id="featured_image"
                               class="form-control-file form-control" accept="image/*">
                        <span class="text-danger small featured_image_error"></span>
                    </div>

                    <div class="form-group">
                        <label><b>Tags</b></label>
                        <input type="text" class="form-control" placeholder="Masukkan Tag"
                               name="tags" data-role="tagsinput">
                    </div>

                    <div class="form-group">
                        <label><b>Visibilitas</b></label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" name="visibility" id="visPublic"
                                   class="custom-control-input" value="1" checked>
                            <label for="visPublic" class="custom-control-label">Publik</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="visibility" id="visPrivate"
                                   class="custom-control-input" value="0">
                            <label for="visPrivate" class="custom-control-label">Privat</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary" id="submitBtn">
            <span id="submitText">Buat Artikel</span>
            <span id="submitSpinner" style="display:none;">
                <span class="spinner-border spinner-border-sm mr-1"></span> Menyimpan...
            </span>
        </button>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
<link rel="stylesheet" href="/extra-assets/summernote/summernote-bs4.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="/extra-assets/summernote/summernote-bs4.min.js"></script>
<script src="/extra-assets/summernote/lang/summernote-id-ID.min.js"></script>
<script>
$(document).ready(function () {

    // Init Summernote
    $('#content').summernote({
        height: 300,
        lang: 'id-ID',
        toolbar: [
            ['style',  ['style']],
            ['font',   ['bold', 'underline', 'clear']],
            ['color',  ['color']],
            ['para',   ['ul', 'ol', 'paragraph']],
            ['table',  ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view',   ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Preview gambar sebelum upload
    $('#featured_image').on('change', function () {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#post-featured-image-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });

    // Submit via AJAX
    $('#addPostForm').on('submit', function (e) {
        e.preventDefault();

        // Sync Summernote ke textarea sebelum kirim
        $('#content').val($('#content').summernote('code'));

        // Reset error
        $('.text-danger.small').text('');
        $('#form-alert').hide();

        // Loading state
        $('#submitText').hide();
        $('#submitSpinner').show();
        $('#submitBtn').prop('disabled', true);

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                // Update CSRF token
                if (res.token) {
                    $('#csrf_field').val(res.token);
                }

                if (res.status === 1) {
                    $('#form-alert').html(
                        '<div class="alert alert-success alert-dismissible fade show">' +
                        '<i class="fa fa-check-circle mr-1"></i> ' + res.msg +
                        ' <a href="<?= route_to('all-posts') ?>" class="alert-link ml-2">Lihat semua artikel</a>' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button></div>'
                    ).show();
                    // Reset form
                    $('#addPostForm')[0].reset();
                    $('#content').summernote('reset');
                    $('#post-featured-image-preview').hide().attr('src', '');
                } else {
                    // Tampilkan error validasi per field
                    if (res.error) {
                        $.each(res.error, function (field, msg) {
                            $('.' + field + '_error').text(msg);
                        });
                    }
                    var errMsg = res.msg || 'Terjadi kesalahan. Periksa kembali form.';
                    $('#form-alert').html(
                        '<div class="alert alert-danger alert-dismissible fade show">' +
                        '<i class="fa fa-exclamation-circle mr-1"></i> ' + errMsg +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button></div>'
                    ).show();
                }
            },
            error: function (xhr) {
                $('#form-alert').html(
                    '<div class="alert alert-danger">Server error: ' + xhr.status + ' ' + xhr.statusText + '</div>'
                ).show();
            },
            complete: function () {
                $('#submitText').show();
                $('#submitSpinner').hide();
                $('#submitBtn').prop('disabled', false);
                // Scroll ke atas untuk lihat alert
                $('html, body').animate({ scrollTop: 0 }, 400);
            }
        });
    });

});
</script>
<?= $this->endSection() ?>
