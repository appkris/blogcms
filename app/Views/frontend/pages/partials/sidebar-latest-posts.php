<div class="widget">
    <h2 class="section-title mb-3">Artikel terbaru</h2>
    <div class="widget-body">
        <?php foreach(get_latest_posts() as $latest_post): ?>
        <li class="media mb-3 align-items-center">
            <a href="<?= route_to('read-post', $latest_post->slug) ?>">
                <img src="/images/posts/thumb_<?= esc($latest_post->featured_image) ?>"
                     class="mr-3 rounded" style="width:60px;height:60px;object-fit:cover;" alt="<?= esc($latest_post->title) ?>">
            </a>
            <div class="media-body ml-2">
                <a href="<?= route_to('read-post', $latest_post->slug) ?>" class="text-dark font-weight-bold">
                    <div class="latest-post-title" style="font-size:1rem;line-height:1.2;">
                        <?= esc($latest_post->title) ?>
                    </div>
                </a>
                <small class="text-muted"><?= convert_time_dmy($latest_post->updated_at) ?></small>
            </div>
        </li>
        <?php endforeach ?>
    </div>
</div>