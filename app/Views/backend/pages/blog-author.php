<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Informasi Author</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Informasi Author
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <input type="hidden" name="id"
                value="<?= isset($author) && is_object($author) && isset($author->id) ? $author->id : '' ?>">

            <div class="w-100 author-thumb-sm d-block text-center position-relative" style="display: inline-block;">

                <!-- Ikon Ubah Foto -->
                <a href="javascript:;" onclick="$('#author_profile_file').click();"
                    class="position-absolute text-white bg-primary rounded-circle d-flex justify-content-center align-items-center"
                    style="top: 10px; right: 40px; width: 30px; height: 30px; font-size: 14px; z-index: 10;">
                    <i class="dw dw-edit-1"></i>
                </a>

                <!-- Ikon Hapus Foto -->
                <a href="javascript:;" id="delete-author-photo"
                    class="position-absolute text-white bg-danger rounded-circle d-flex justify-content-center align-items-center"
                    style="top: 10px; right: 5px; width: 30px; height: 30px; font-size: 14px; z-index: 10;">
                    <i class="fa fa-trash"></i>
                </a>

                <!-- Input File -->
                <input type="file" name="user_profile_file" id="author_profile_file" class="d-none">

                <!-- Foto Author -->
                <img src="<?= (isset($author) && is_object($author) && !empty($author->photo)) ? '/uploads/author/' . $author->photo : '/frontend/images/author.jpg' ?>"
                    alt="" class="avatar-photo ci-avatar-photo" id="author-photo-preview" style="width: 100%;">

                <!-- Nama & Bio -->
                <div class="mt-3">
                    <h2 class="widget-title my-3"><span
                            id="author-name-preview"><?= isset($author) && is_object($author) && isset($author->name) ? esc($author->name) : '' ?></span>
                    </h2>
                    <p class="mb-3 pb-2 text-justify"><span
                            id="author-bio-preview"><?= isset($author) && is_object($author) && isset($author->bio)
                                                        ? esc(strlen($author->bio) > 120 ? substr($author->bio, 0, 117) . '...' : $author->bio)
                                                        : '' ?></span>
                    </p>
                </div>
            </div>
            <!-- Remove duplicate name and bio below the photo, only show in the photo card above -->
            <ul class="widget-list">
                <?php $sm = get_social_media(); ?>
                <div class="text-center mt-2">
                    <?php if (!empty($sm->facebook_url)): ?>
                        <a href="<?= esc($sm->facebook_url) ?>" target="_blank" class="mx-1" title="Facebook">
                            <img loading="lazy" decoding="async" src="/images/social/1.png" alt="facebook">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->twitter_url)): ?>
                        <a href="<?= esc($sm->twitter_url) ?>" target="_blank" class="mx-1" title="Twitter">
                            <img loading="lazy" decoding="async" src="/images/social/2.png" alt="twitter">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->instagram_url)): ?>
                        <a href="<?= esc($sm->instagram_url) ?>" target="_blank" class="mx-1" title="Instagram">
                            <img loading="lazy" decoding="async" src="/images/social/3.png" alt="instagram">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->youtube_url)): ?>
                        <a href="<?= esc($sm->youtube_url) ?>" target="_blank" class="mx-1" title="YouTube">
                            <img loading="lazy" decoding="async" src="/images/social/4.png" alt="youtube">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->tiktok_url)): ?>
                        <a href="<?= esc($sm->tiktok_url) ?>" target="_blank" class="mx-1" title="TikTok">
                            <img loading="lazy" decoding="async" src="/images/social/5.png" alt="tiktok">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->linkedin_url)): ?>
                        <a href="<?= esc($sm->linkedin_url) ?>" target="_blank" class="mx-1" title="LinkedIn">
                            <img loading="lazy" decoding="async" src="/images/social/6.png" alt="facebook">
                        </a>
                    <?php endif; ?>
                </div>
            </ul>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <div class="tab-content">
                        <div class="pd-20">
                            <form action="<?= route_to('admin.update-author') ?>" method="post" id="author_details_form"
                                enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="csrf_token" value="<?= csrf_hash() ?>">
                                <input type="hidden" name="id"
                                    value="<?= isset($author) && is_object($author) && isset($author->id) ? $author->id : '' ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Author</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Nama author"
                                                value="<?= isset($author) && is_object($author) && isset($author->name) ? esc($author->name) : '' ?>">
                                            <span class="text-danger error-text name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="url">Url halaman utama</label>
                                            <input class="form-control" type="text" placeholder="<?= base_url('/') ?>"
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="text">Jenis konten blog</label>
                                            <select name="content_type_id" class="custom-select col-12">
                                                <option value="">Pilih...</option>
                                                <?php foreach ($types as $type): ?>
                                                    <option value="<?= $type['id'] ?>" <?= ($selectedTypeId == $type['id']) ? 'selected' : '' ?>>
                                                        <?= esc($type['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="bio">Tentang</label>
                                    <textarea name="bio" id="bio" cols="30" rows="10" class="form-control"
                                        placeholder="Masukan tentang author"><?= isset($author) && is_object($author) && isset($author->bio) ? esc($author->bio) : '' ?></textarea>
                                    <span class="text-danger error-text bio_error"></span>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Timeline Tab End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->section('scripts') ?>
<script src="/assets/js/author.js"></script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>