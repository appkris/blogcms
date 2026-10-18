<footer class="bg-dark mt-5">
    <div class="container section">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <a class="d-inline-block mb-4 pb-2" href="<?= route_to('BlogController::index') ?>">
                    <img loading="prelaod" decoding="async" class="img-fluid"
                        src="/images/blogs/<?= get_settings()->blog_logo ?>" alt="<?= get_settings()->blog_title ?> logo"
                        style="max-width: 170px;">
                </a>
                <ul class="p-0 d-flex flex-wrap justify-content-center navbar-footer mb-0 list-unstyled">
                    <?php foreach (get_footer_pages() as $fp): ?>
                        <li class="nav-item my-0">
                            <a class="nav-link" href="<?= base_url('page/' . $fp->slug) ?>"><?= esc($fp->title) ?></a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="copyright bg-dark content">
        &copy; Copyright
        <script>document.write(new Date().getFullYear())</script> - <?= get_settings()->blog_title ?>. All rights
        reserved.
    </div>
</footer>