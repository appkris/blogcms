<?= $this->extend('frontend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>
<?= $this->section('page_meta') ?>
<meta name="robots" content="index,follow" />
<meta name="title" content="<?= get_settings()->blog_title ?>" />
<meta name="description" content="<?= get_settings()->blog_meta_description ?>" />
<meta name="author" content="<?= get_settings()->blog_title ?>" />
<link rel="canonical" href="<?= base_url() ?>" />
<meta property="og:title" content="<?= get_settings()->blog_title ?>" />
<meta property="og:type" content="website" />
<meta property="og:description" content="<?= get_settings()->blog_meta_description ?>" />
<meta property="og:url" content="<?= base_url() ?>" />
<meta property="og:image" content="<?= base_url('images/blogs/' . get_settings()->blog_logo) ?>" />
<meta name="twitter:domain" content="<?= base_url() ?>" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" property="og:title" itemprop="name" content="<?= get_settings()->blog_title ?>" />
<meta name="twitter:description" property="og:description" itemprop="description"
  content="<?= get_settings()->blog_meta_description ?>" />
<meta name="twitter:image" content="<?= base_url('images/blogs/' . get_settings()->blog_logo) ?>" />
<?= $this->endSection() ?>

<div class="row no-gutters-lg">
    <div class="col-12">
        <h2 class="section-title">Artikel Terbaru</h2>
    </div>

    <div class="col-lg-8 mb-5 mb-lg-0">
        <div class="row">
            <?php if ($mainPost): ?>

                <!-- Main post (featured) - hanya halaman 1 -->
                <div class="col-12 mb-4">
                    <article class="card article-card article-card-sm">
                        <a href="<?= route_to('read-post', $mainPost->slug) ?>">
                            <div class="card-image">
                                <div class="post-info">
                                    <span class="text-uppercase"><?= convert_time_dmy($mainPost->updated_at) ?></span>
                                    <span class="text-uppercase">baca <?= time_to_read($mainPost->content) ?> menit</span>
                                </div>
                                <img loading="lazy" decoding="async"
                                     src="images/posts/<?= esc($mainPost->featured_image) ?>"
                                     alt="Post Thumbnail" class="w-100">
                            </div>
                        </a>
                        <div class="card-body px-0 pb-1">
                            <ul class="post-meta mb-2">
                                <?php $tags = get_tags_single_post($mainPost->id); ?>
                                <?php if (!empty($tags)): ?>
                                    <?php foreach ($tags as $tag): ?>
                                        <?php if (trim($tag) !== ''): ?>
                                            <li><a href="<?= route_to('tag-post', trim($tag)) ?>">#<?= esc(trim($tag)) ?></a></li>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <li><a href="#!">No tags</a></li>
                                <?php endif ?>
                            </ul>
                            <h2 class="h2">
                                <a class="post-title" href="<?= route_to('read-post', $mainPost->slug) ?>"><?= esc($mainPost->title) ?></a>
                            </h2>
                            <p class="card-text"><?= wrapper_limit_text($mainPost->content, 35) ?></p>
                            <div class="content">
                                <a class="read-more-btn" href="<?= route_to('read-post', $mainPost->slug) ?>">Artikel Lengkap</a>
                            </div>
                        </div>
                    </article>
                </div>

            <?php endif ?>

            <!-- Grid posts (semua halaman) -->
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post_data): ?>
                    <div class="col-md-6 mb-4">
                        <article class="card article-card article-card-sm">
                            <a href="<?= route_to('read-post', $post_data->slug) ?>">
                                <div class="card-image">
                                    <div class="post-info">
                                        <span class="text-uppercase"><?= convert_time_dmy($post_data->updated_at) ?></span>
                                        <span class="text-uppercase">baca <?= time_to_read($post_data->content) ?> menit</span>
                                    </div>
                                    <img loading="lazy" decoding="async"
                                         src="images/posts/thumb_<?= esc($post_data->featured_image) ?>"
                                         alt="Post Thumbnail" class="w-100"
                                         style="aspect-ratio:16/9;object-fit:cover;width:100%;border-radius:8px;">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-0">
                                <ul class="post-meta mb-2">
                                    <?php $tags = get_tags_single_post($post_data->id); ?>
                                    <?php if (!empty($tags)): ?>
                                        <?php foreach ($tags as $tag): ?>
                                            <?php if (trim($tag) !== ''): ?>
                                                <li><a href="<?= route_to('tag-post', trim($tag)) ?>">#<?= esc(trim($tag)) ?></a></li>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <li><a href="#!">No tags</a></li>
                                    <?php endif ?>
                                </ul>
                                <h3>
                                    <a class="post-title" href="<?= route_to('read-post', $post_data->slug) ?>"><?= esc($post_data->title) ?></a>
                                </h3>
                                <p class="card-text"><?= wrapper_limit_text($post_data->content) ?></p>
                                <div class="content">
                                    <a class="read-more-btn" href="<?= route_to('read-post', $post_data->slug) ?>">Artikel Lengkap</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach ?>
            <?php elseif (!$mainPost): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada postingan untuk ditampilkan.</div>
                </div>
            <?php endif ?>

            <!-- Pagination: tampil di semua halaman -->
            <?php if ($pager->getPageCount() > 1): ?>
            <div class="col-12 mt-3">
                <ul class="pagination justify-content-center">
                    <?php if ($pager->getCurrentPage() > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $pager->getPreviousPageURI() ?>">&laquo;</a>
                        </li>
                    <?php endif ?>

                    <?php for ($i = 1; $i <= $pager->getPageCount(); $i++): ?>
                        <li class="page-item <?= $i === $pager->getCurrentPage() ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI($i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor ?>

                    <?php if ($pager->hasMore()): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $pager->getNextPageURI() ?>">&raquo;</a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
            <?php endif ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="widget-blocks">
            <div class="row">
                <div class="col-lg-12">
                    <?php if (function_exists('display_sidebar_ad')): ?>
                        <?= display_sidebar_ad() ?>
                    <?php endif ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?php include('partials/random-post.php') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?php include('partials/sidebar-categories.php') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?php include('partials/sidebar-tags.php') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
