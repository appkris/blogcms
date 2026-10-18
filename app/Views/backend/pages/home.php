<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="card-box pd-20 height-100-p mb-30">
					<div class="row align-items-center">
						<div class="col-md-3">
							<img src="<?= get_user()->picture === null ? "/images/users/default-avatar.png" : '/images/users/' . get_user()->picture ?>" alt="">
						</div>
						<div class="col-md-8">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Selamat datang kembali!
								<div class="weight-600 font-30 text-blue"><?= get_user()->name ?></div>
							</h4>
							<p class="font-18 max-width-600">
								Selamat datang di dashboard admin blog Anda. Di sini Anda dapat 
								mengelola semua konten, kategori, dan pengaturan blog dengan mudah.
								Mulai dengan membuat post baru atau mengatur kategori blog Anda.
							</p>
						</div>
					</div>
				</div>
				<div class="row pb-10">
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">0</div>
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
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">0</div>
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
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">0</div>
									<div class="font-14 text-secondary weight-500">
										Terpublikasi
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon">
										<i class="icon-copy dw dw-checked"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark">0</div>
									<div class="font-14 text-secondary weight-500">Draft Artikel</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#09cc06" style="color: rgb(9, 204, 6);">
										<i class="icon-copy dw dw-edit-file"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				

<?= $this->endSection() ?>