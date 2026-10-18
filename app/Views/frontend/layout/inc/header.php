<header class="modern-navigation">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light px-0">
            <a class="navbar-brand order-1 py-0" href="<?= route_to('BlogController::index') ?>">
                <img loading="preload" decoding="async" class="img-fluid"
                    src="/images/blogs/<?= get_settings()->blog_logo ?>" alt="<?= get_settings()->blog_title ?> logo" style="max-width: 170px;">
            </a>
            
            <div class="navbar-actions order-3 ml-0 ml-md-4">
                <button aria-label="navbar toggler" class="navbar-toggler border-0" type="button" data-toggle="collapse"
                    data-target="#navigation"> <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <form action="<?= route_to('search-post') ?>" method="get"
                class="search order-lg-3 order-md-2 order-3 ml-auto">
                <input id="search-query" name="q" type="search" placeholder="Cari artikel..."
                    value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
            </form>
            
            <!-- Social Media Icons -->
            <div class="social-icons order-lg-4 d-none d-lg-flex">
                <?php $social = get_social_media(); ?>
                <?php if($social->facebook_url): ?>
                    <a href="<?= $social->facebook_url ?>" target="_blank" class="social-link facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                <?php endif; ?>
                <?php if($social->twitter_url): ?>
                    <a href="<?= $social->twitter_url ?>" target="_blank" class="social-link twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                <?php endif; ?>
                <?php if($social->instagram_url): ?>
                    <a href="<?= $social->instagram_url ?>" target="_blank" class="social-link instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                <?php endif; ?>
                <?php if($social->youtube_url): ?>
                    <a href="<?= $social->youtube_url ?>" target="_blank" class="social-link youtube">
                        <i class="fab fa-youtube"></i>
                    </a>
                <?php endif; ?>
                <?php if($social->tiktok_url): ?>
                    <a href="<?= $social->tiktok_url ?>" target="_blank" class="social-link tiktok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                <?php endif; ?>
                <?php if($social->linkedin_url): ?>
                    <a href="<?= $social->linkedin_url ?>" target="_blank" class="social-link linkedin">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="collapse navbar-collapse text-center order-lg-2 order-4" id="navigation">
                <ul class="navbar-nav mx-auto mt-3 mt-lg-0">
                    <li class="nav-item"> <a class="nav-link" href="<?= route_to('BlogController::index') ?>">Home</a>
                    </li>
                    <?php foreach (get_parent_categories() as $parent_category): ?>
                        <li
                            class="nav-item <?= count(get_parent_subcategories($parent_category->id)) > 0 ? "dropdown" : "" ?>">
                            <a class="nav-link <?= count(get_parent_subcategories($parent_category->id)) > 0 ? "dropdown-toggle" : "" ?>"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= $parent_category->name ?>
                            </a>
                            <?php if (count(get_parent_subcategories($parent_category->id)) > 0): ?>
                                <div class="dropdown-menu">
                                    <?php foreach (get_parent_subcategories($parent_category->id) as $parent_subcategory): ?>
                                        <a class="dropdown-item"
                                            href="<?= route_to('category-post', $parent_subcategory->slug) ?>"><?= $parent_subcategory->name ?></a>
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                    <?php foreach (get_dependent_subcategories() as $dependent_subcategory): ?>
                        <li class="nav-item"> <a class="nav-link"
                                href="<?= route_to('category-post', $dependent_subcategory->slug) ?>"><?= $dependent_subcategory->name ?></a>
                        </li>
                    <?php endforeach ?>
                    <li class="nav-item"> <a class="nav-link" href="<?= route_to('contact-us') ?>">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>