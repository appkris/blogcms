<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="card-box pd-20 mb-30 shadow-sm">
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="mr-3">
                <img src="<?= get_user()->picture === null ? '/images/users/default-avatar.png' : '/images/users/' . get_user()->picture ?>" 
                     alt="Avatar" class="rounded-circle border border-primary shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
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

<!-- Kartu Statistik Metrik -->
<div class="row pb-15">
    <div class="col-xl-3 col-lg-3 col-md-6 mb-25">
        <div class="card-box p-4 h-100 shadow-sm border-0 d-flex align-items-center justify-content-between" style="border-radius: 10px; transition: transform 0.2s ease;">
            <div>
                <div class="weight-700 font-28 text-dark mb-1"><?= (int) ($totalPosts ?? 0) ?></div>
                <div class="font-14 text-secondary weight-500">Total Artikel</div>
                <small class="text-success font-12"><i class="icon-copy dw dw-arrow-up2"></i> Konten dibuat</small>
            </div>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(0, 236, 207, 0.15); color: #00b8a2;">
                <i class="icon-copy dw dw-file font-24"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-25">
        <div class="card-box p-4 h-100 shadow-sm border-0 d-flex align-items-center justify-content-between" style="border-radius: 10px; transition: transform 0.2s ease;">
            <div>
                <div class="weight-700 font-28 text-dark mb-1"><?= (int) ($totalCategories ?? 0) ?></div>
                <div class="font-14 text-secondary weight-500">Kategori</div>
                <small class="text-info font-12"><i class="icon-copy dw dw-folder-1"></i> Topik aktif</small>
            </div>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(255, 91, 91, 0.15); color: #ff5b5b;">
                <i class="icon-copy dw dw-folder font-24"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-25">
        <div class="card-box p-4 h-100 shadow-sm border-0 d-flex align-items-center justify-content-between" style="border-radius: 10px; transition: transform 0.2s ease;">
            <div>
                <div class="weight-700 font-28 text-dark mb-1"><?= (int) ($totalPublished ?? 0) ?></div>
                <div class="font-14 text-secondary weight-500">Terpublikasi</div>
                <small class="text-success font-12"><i class="icon-copy dw dw-checked"></i> Live publik</small>
            </div>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(9, 204, 6, 0.15); color: #09cc06;">
                <i class="icon-copy dw dw-checked font-24"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-25">
        <div class="card-box p-4 h-100 shadow-sm border-0 d-flex align-items-center justify-content-between" style="border-radius: 10px; transition: transform 0.2s ease;">
            <div>
                <div class="weight-700 font-28 text-dark mb-1"><?= (int) ($totalDraft ?? 0) ?></div>
                <div class="font-14 text-secondary weight-500">Draft Artikel</div>
                <small class="text-warning font-12"><i class="icon-copy dw dw-clock"></i> Menunggu rilis</small>
            </div>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(255, 153, 0, 0.15); color: #ff9900;">
                <i class="icon-copy dw dw-edit-file font-24"></i>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Statistik Blog (Dummy Interaktif) -->
<div class="row">
    <div class="col-xl-8 col-lg-8 mb-30">
        <div class="card-box pd-20 shadow-sm h-100" style="border-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center mb-20 flex-wrap">
                <div>
                    <h5 class="h5 mb-1 text-blue font-16"><i class="icon-copy dw dw-analytics-2 mr-2"></i>Statistik Kunjungan & Pembaca</h5>
                    <p class="font-12 text-muted mb-0">Estimasi tren pembaca 7 hari terakhir (Data Simulasi)</p>
                </div>
                <div class="d-flex align-items-center mt-2 mt-sm-0">
                    <span class="badge badge-success px-2 py-1 mr-2 font-12"><i class="icon-copy dw dw-up-arrow-2"></i> +24.8%</span>
                    <span class="badge badge-light-primary text-primary font-12 px-2 py-1">Mingguan</span>
                </div>
            </div>
            <div id="chart-visitor-analytics" style="min-height: 290px;"></div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-4 mb-30">
        <div class="card-box pd-20 shadow-sm h-100" style="border-radius: 10px;">
            <div class="mb-20">
                <h5 class="h5 mb-1 text-blue font-16"><i class="icon-copy dw dw-pie-chart1 mr-2"></i>Sumber Pembaca</h5>
                <p class="font-12 text-muted mb-0">Distribusi asal trafik blog</p>
            </div>
            <div id="chart-traffic-sources" style="min-height: 250px;"></div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-between text-muted font-12">
                <span><i class="fa fa-circle text-primary mr-1"></i> Google Search (55%)</span>
                <span><i class="fa fa-circle text-success mr-1"></i> Sosmed (25%)</span>
                <span><i class="fa fa-circle text-warning mr-1"></i> Langsung (20%)</span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/apexcharts/apexcharts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Line / Area Chart Kunjungan Blog (Dummy 7 Hari)
        var optionsVisitor = {
            series: [{
                name: 'Kunjungan Halaman (Page Views)',
                data: [420, 580, 510, 790, 890, 1150, 1340]
            }, {
                name: 'Pengunjung Unik (Visitors)',
                data: [210, 310, 280, 430, 520, 680, 820]
            }],
            chart: {
                height: 290,
                type: 'area',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#1b00ff', '#00eccf'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                labels: {
                    style: { colors: '#6c757d', fontSize: '12px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#6c757d', fontSize: '12px' }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'light',
                x: { format: 'dd/MM/yy' }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };

        var chartVisitor = new ApexCharts(document.querySelector("#chart-visitor-analytics"), optionsVisitor);
        chartVisitor.render();

        // 2. Donut Chart Sumber Trafik
        var optionsTraffic = {
            series: [55, 25, 20],
            chart: {
                type: 'donut',
                height: 230
            },
            labels: ['Search Engine', 'Social Media', 'Direct Visit'],
            colors: ['#1b00ff', '#09cc06', '#ff9900'],
            legend: { show: false },
            dataLabels: { enabled: true },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { width: 200 }
                }
            }]
        };

        var chartTraffic = new ApexCharts(document.querySelector("#chart-traffic-sources"), optionsTraffic);
        chartTraffic.render();
    });
</script>
<?= $this->endSection() ?>