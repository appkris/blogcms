<div class="widget">
    <div class="widget-body">
        <?php
        $authorImg = (isset($author) && is_object($author) && !empty($author->photo))
            ? base_url('uploads/author/' . $author->photo)
            : base_url('frontend/images/author.jpg');
        $authorName = (isset($author) && is_object($author) && !empty($author->name))
            ? esc($author->name)
            : 'Author';
        $authorBio = (isset($author) && is_object($author) && !empty($author->bio))
            ? esc($author->bio)
            : 'No bio yet.';
        // Batasi bio max 120 karakter, tambahkan ... jika lebih panjang
        $maxBioLength = 120;
        if (mb_strlen($authorBio) > $maxBioLength) {
            $authorBio = mb_substr($authorBio, 0, $maxBioLength) . '...';
        }
        ?>
        <img loading="lazy" decoding="async" src="<?= $authorImg ?>" alt="About Me"
            class="author-thumb-sm mx-auto d-block">
        <h2 class="widget-title my-3"><?= $authorName ?></h2>
        <p class="mb-3 pb-2 text-justify"><?= $authorBio ?></p>
        <a href=<?= route_to('page-show', 'tentang-kami') ?> class="btn btn-sm btn-outline-primary">Tentang</a>
    </div>
</div>