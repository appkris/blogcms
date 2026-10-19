<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="card-box pd-20 mb-25">
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="mr-3">
                <img src="<?= get_user()->picture === null ? '/images/users/default-avatar.png' : '/images/users/' . get_user()->picture ?>" 
                     alt="Avatar" class="rounded-circle border border-primary shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
            </div>
            <div>
                <h5 class="weight-600 font-20 text-capitalize mb-1">
                    Selamat Datang, <span class="text-blue"><?= esc(get_user()->name) ?></span>!
                </h5>
                <p class="font-14 text-muted mb-0">
                    Ringkasan performa dan aktivitas blog Anda hari ini.
                </p>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="<?= route_to('new-post') ?>" class="btn btn-primary btn-sm mr-2 shadow-sm">
                <i class="icon-copy dw dw-add"></i> Tulis Artikel
            </a>
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="icon-copy dw dw-eye"></i> Lihat Blog
            </a>
        </div>
    </div>
</div>

<div class="row pb-10">
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3 shadow-sm">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= (int) ($totalPosts ?? 0) ?></div>
                    <div class="font-14 text-secondary weight-500">
                        Total Artikel
                    </div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#00eccf" style="color: rgb(0, 236, 207);">
                        <i class="icon-copy dw dw-file"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3 shadow-sm">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= (int) ($totalCategories ?? 0) ?></div>
                    <div class="font-14 text-secondary weight-500">
                        Kategori
                    </div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#ff5b5b" style="color: rgb(255, 91, 91);">
                        <i class="icon-copy dw dw-folder"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3 shadow-sm">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= (int) ($totalPublished ?? 0) ?></div>
                    <div class="font-14 text-secondary weight-500">
                        Terpublikasi
                    </div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#09cc06" style="color: rgb(9, 204, 6);">
                        <i class="icon-copy dw dw-checked"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3 shadow-sm">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= (int) ($totalDraft ?? 0) ?></div>
                    <div class="font-14 text-secondary weight-500">Draft Artikel</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#ff9900" style="color: rgb(255, 153, 0);">
                        <i class="icon-copy dw dw-edit-file"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Placeholder Area: Siap untuk Grafik Statistik Blog -->
<div class="row">
    <div class="col-12 mb-20">
        <div class="card-box pd-20 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-15">
                <h5 class="h5 mb-0 text-blue font-16"><i class="icon-copy dw dw-analytics-2 mr-2"></i>Statistik Blog</h5>
                <span class="badge badge-light-primary text-primary font-12 py-1 px-2">Akan Datang</span>
            </div>
            <div class="text-center py-4 text-muted bg-light rounded" style="border: 2px dashed #e3e7ec;">
                <i class="icon-copy dw dw-chart1 font-30 mb-2 text-secondary"></i>
                <p class="mb-0 font-14">Area grafik statistik (kunjungan, pembaca harian & artikel populer) siap diintegrasikan di sini.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>