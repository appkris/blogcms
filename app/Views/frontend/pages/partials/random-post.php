<div class="widget">
    <div class="widget-body">
        <ul class="widget-list">
            <?php $random_posts = get_random_posts(4); ?>
            <?php if (!empty($random_posts)): ?>
                <?php $first = array_shift($random_posts); ?>

                <div class="card-image">
                    <img loading="lazy" decoding="async" src="/images/posts/<?= esc($first->featured_image) ?>"
                        alt="Post Thumbnail" class="w-100">
                    <div class="post-info">
                        <span class="text-uppercase">baca <?= time_to_read($first->content) ?> menit</span>
                    </div>
                    <div class="card-body px-0 pb-1">
                        <h3><a class="post-title post-title-sm"
                                href="<?= route_to('read-post', $first->slug) ?>"><?= esc($first->title) ?></a>
                        </h3>
                        <p class="card-text"><?= wrapper_limit_text($first->content, 10) ?></p>
                        <div class="content">
                            <a class="read-more-btn" href="<?= route_to('read-post', $first->slug) ?>">Artikel Lengkap</a>
                        </div>
                    </div>
                </div>
                <hr class="solid">
                <?php foreach ($random_posts as $post): ?>
                    <a class="media align-items-center mb-3" href="<?= route_to('read-post', $post->slug) ?>">
                        <?php if (!empty($post->featured_image)): ?>
                            <img loading="lazy" decoding="async" src="/images/posts/thumb_<?= esc($post->featured_image) ?>"
                                alt="Post Thumbnail" class="mr-3 rounded" style="object-fit:cover;">
                        <?php else: ?>
                            <span class="image-fallback image-fallback-xs">No Image Specified</span>
                        <?php endif; ?>
                        <div class="latest-post-title">
                            <p style="margin-top:-5px"><?= esc($post->title) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>