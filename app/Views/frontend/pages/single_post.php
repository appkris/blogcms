<?= $this->extend('frontend/layout/pages-layout.php') ?>
<?= $this->section('page_meta') ?>
<?php 
    $postDescription = !empty($post->meta_description) ? $post->meta_description : word_limiter(strip_tags($post->content ?? ''), 30);
    $postKeywords = !empty($post->meta_keywords) ? $post->meta_keywords : (!empty($post->tags) ? $post->tags : '');
    $imageUrl = !empty($post->featured_image) ? base_url('images/posts/' . $post->featured_image) : (!empty(get_settings()->blog_logo) ? base_url('images/blogs/' . get_settings()->blog_logo) : '');
    $authorName = !empty($author->name) ? $author->name : get_settings()->blog_title;
    $publishedDate = !empty($post->created_at) ? date('c', strtotime($post->created_at)) : '';
    $modifiedDate = !empty($post->updated_at) ? date('c', strtotime($post->updated_at)) : $publishedDate;
?>
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1" />
<meta name="description" content="<?= esc($postDescription) ?>" />
<?php if (!empty($postKeywords)): ?>
<meta name="keywords" content="<?= esc($postKeywords) ?>" />
<?php endif; ?>
<meta name="author" content="<?= esc($authorName) ?>" />
<link rel="canonical" href="<?= current_url() ?>" />

<!-- Open Graph / Facebook -->
<meta property="og:locale" content="id_ID" />
<meta property="og:type" content="article" />
<meta property="og:site_name" content="<?= esc(get_settings()->blog_title) ?>" />
<meta property="og:title" content="<?= esc($post->title) ?>" />
<meta property="og:description" content="<?= esc($postDescription) ?>" />
<meta property="og:url" content="<?= current_url() ?>" />
<?php if (!empty($imageUrl)): ?>
<meta property="og:image" content="<?= $imageUrl ?>" />
<meta property="og:image:alt" content="<?= esc($post->title) ?>" />
<?php endif; ?>
<?php if (!empty($publishedDate)): ?>
<meta property="article:published_time" content="<?= $publishedDate ?>" />
<?php endif; ?>
<?php if (!empty($modifiedDate)): ?>
<meta property="article:modified_time" content="<?= $modifiedDate ?>" />
<?php endif; ?>
<meta property="article:author" content="<?= esc($authorName) ?>" />
<?php if (!empty($post->category_name)): ?>
<meta property="article:section" content="<?= esc($post->category_name) ?>" />
<?php endif; ?>
<?php if (!empty($post->tags)): ?>
<?php foreach (array_filter(array_map('trim', explode(',', $post->tags))) as $tag): ?>
<meta property="article:tag" content="<?= esc($tag) ?>" />
<?php endforeach; ?>
<?php endif; ?>

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:domain" content="<?= parse_url(base_url(), PHP_URL_HOST) ?>" />
<meta name="twitter:title" content="<?= esc($post->title) ?>" />
<meta name="twitter:description" content="<?= esc($postDescription) ?>" />
<?php if (!empty($imageUrl)): ?>
<meta name="twitter:image" content="<?= $imageUrl ?>" />
<meta name="twitter:image:alt" content="<?= esc($post->title) ?>" />
<?php endif; ?>

<!-- JSON-LD Structured Data: Article & BreadcrumbList -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BlogPosting",
      "@id": "<?= current_url() ?>#article",
      "headline": <?= json_encode($post->title) ?>,
      "description": <?= json_encode($postDescription) ?>,
      "url": "<?= current_url() ?>",
      "mainEntityOfPage": "<?= current_url() ?>",
      <?php if (!empty($publishedDate)): ?>"datePublished": "<?= $publishedDate ?>",<?php endif; ?>
      <?php if (!empty($modifiedDate)): ?>"dateModified": "<?= $modifiedDate ?>",<?php endif; ?>
      "author": {
        "@type": "Person",
        "name": <?= json_encode($authorName) ?>
      },
      "publisher": {
        "@type": "Organization",
        "name": <?= json_encode(get_settings()->blog_title) ?><?php if (!empty(get_settings()->blog_logo)): ?>,
        "logo": {
          "@type": "ImageObject",
          "url": "<?= base_url('images/blogs/' . get_settings()->blog_logo) ?>"
        }<?php endif; ?>
      }<?php if (!empty($imageUrl)): ?>,
      "image": {
        "@type": "ImageObject",
        "url": "<?= $imageUrl ?>"
      }<?php endif; ?><?php if (!empty($post->category_name)): ?>,
      "articleSection": <?= json_encode($post->category_name) ?><?php endif; ?><?php if (!empty($post->tags)): ?>,
      "keywords": <?= json_encode($post->tags) ?><?php endif; ?>
    },
    {
      "@type": "BreadcrumbList",
      "@id": "<?= current_url() ?>#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Beranda",
          "item": "<?= base_url() ?>"
        }<?php if (!empty($post->category_name) && !empty($post->category_slug)): ?>,
        {
          "@type": "ListItem",
          "position": 2,
          "name": <?= json_encode($post->category_name) ?>,
          "item": "<?= route_to('category-post', $post->category_slug) ?>"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": <?= json_encode($post->title) ?>,
          "item": "<?= current_url() ?>"
        }<?php else: ?>,
        {
          "@type": "ListItem",
          "position": 2,
          "name": <?= json_encode($post->title) ?>,
          "item": "<?= current_url() ?>"
        }<?php endif; ?>
      ]
    }
  ]
}
</script>
<?= $this->endSection() ?>
<?= $this->section('breadcrumbs') ?>
<nav class="modern-breadcrumb mb-4">
    <ol class="breadcrumb-list">
        <li class="breadcrumb-item">
            <a href="<?= base_url('/') ?>" class="breadcrumb-link">
                <i class="fas fa-home"></i>
                <span class="d-none d-sm-inline">Beranda</span>
            </a>
        </li>
        <?php if (!empty($post->category_slug) && !empty($post->category_name)): ?>
            <li class="breadcrumb-item">
                <i class="fa-regular fa-folder-open breadcrumb-separator"></i>
                <a href="<?= route_to('category-post', esc($post->category_slug)) ?>" class="breadcrumb-link" title="<?= esc($post->category_name) ?>">
                    <span><?= wrapper_limit_text(esc($post->category_name), 3) ?></span>
                </a>
            </li>
        <?php endif; ?>
        <li class="breadcrumb-item active">
            <i class="fa-regular fa-file breadcrumb-separator"></i>
            <span class="breadcrumb-current" title="<?= esc($post->title) ?>"><?= wrapper_limit_text(esc($post->title), 5) ?></span>
        </li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8 mb-5 mb-lg-0">
        <article>
            <img loading="lazy" decoding="async" src="/images/posts/<?= $post->featured_image ?>" alt="Post Thumbnail"
                class="w-100">
            <ul class="post-meta mb-2 mt-4">
                <li class="d-flex flex-wrap fs-normal fs-sm-smaller">
                     <span class="me-2"><i class="fa-regular fa-calendar"></i> <?= convert_time_dmy($post->updated_at) ?></span>
                     <span class="me-2">&nbsp;·&nbsp;<i class="fa-regular fa-clock"></i> <?= time_to_read($post->content) ?> Menit</span>
                     <span>&nbsp;·&nbsp;<i class="fa-regular fa-user"></i> <?= $author->name ?></span>
                </li>
            </ul>
            <h2 class="my-3"><?= $post->title ?></h2>
            <?php if (count(get_tags_single_post($post->id))): ?>
                <ul class="post-meta mb-4">
                    <?php foreach (get_tags_single_post($post->id) as $tag_single_post): ?>

                        <li>
                            <a href="/tag/<?= $tag_single_post ?>">#<?= $tag_single_post ?></a>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <div class="content text-left">
                <?= $post->content ?>
                
                <?php if(function_exists('display_content_ad')): ?>
                    <div class="mt-4 mb-4">
                        <?= display_content_ad() ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty(get_related_post_by_post_id($post->id))): ?>
                <div class="mt-3" id="related-posts">
                    <h3>Baca juga</h3>
                    <div class="widget-body">
                        <div class="widget-list">
                            <?php foreach (get_related_post_by_post_id($post->id) as $related_post): ?>
                                <a class="media align-items-center" href="<?= route_to('read-post', $related_post->slug) ?>">
                                    <img loading="lazy" decoding="async"
                                        src="/images/posts/thumb_<?= $related_post->featured_image ?>" alt="Post Thumbnail"
                                        class="w-100">
                                    <div class="media-body ml-3">
                                        <h3 style="margin-top:-5px"><?= $related_post->title ?></h3>
                                        <p class="mb-0 small"></p>
                                        <p><?= wrapper_limit_text($related_post->content) ?></p>
                                    </div>
                                </a>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </article>
        <!-- Share buttons -->
        <div class="share-buttons my-4 p-3 bg-light rounded d-flex align-items-center flex-wrap gap-2">
            <span class="share-text fw-bold text-secondary me-2"><i class="fas fa-share-alt me-1"></i> Bagikan:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" rel="noopener noreferrer" class="btn-share facebook" style="background:#1877f2" title="Bagikan ke Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($post->title) ?>" target="_blank" rel="noopener noreferrer" class="btn-share twitter" title="Bagikan ke X (Twitter)">
                <i class="fab fa-x-twitter"></i>
            </a>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($post->title . ' ' . current_url()) ?>" target="_blank" rel="noopener noreferrer" class="btn-share whatsapp" title="Bagikan ke WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(current_url()) ?>" target="_blank" rel="noopener noreferrer" class="btn-share linkedin" style="background:#0a66c2" title="Bagikan ke LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <button type="button" class="btn-share copy-link border-0" title="Salin Tautan" onclick="copyToClipboard('<?= current_url() ?>');">
                <i class="fas fa-link"></i>
            </button>
            <span id="linkCopied" class="link-copied text-success fw-bold ms-2" style="display:none;"><i class="fas fa-check-circle"></i> Tautan disalin!</span>
        </div>
        <div class="prev-next-posts mt-3 mb-3">
            <div class="row justify-content-between p-4">
                <div class="col-md-6 mb-2">
                    <?php if (!empty(get_previous_post($post->id))): ?>
                        <div>
                            <a href="<?= route_to('read-post', get_previous_post($post->id)->slug) ?>">« <?= get_previous_post($post->id)->title ?></a>
                        </div>
                    <?php endif ?>
                </div>
                <div class="col-md-6 mb-2 text-md-right">
                    <?php if (!empty(get_next_post($post->id))): ?>
                        <div>
                            <a href="<?= route_to('read-post', get_next_post($post->id)->slug) ?>"><?= get_next_post($post->id)->title ?> »</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- Komentar -->
        <?php include('partials/comments.php') ?>

    </div>
    <div class="col-lg-4 sidebar-col">
        <div class="sidebar-wrapper">

            <!-- Widget statis: About Me + Latest Post -->
            <div id="sidebar-static">
                <div class="mb-4">
                    <?php include('partials/sidebar-author.php') ?>
                </div>
                <?php if(function_exists('display_sidebar_ad')): ?>
                <div class="mb-4">
                    <?= display_sidebar_ad() ?>
                </div>
                <?php endif; ?>
                <div class="mb-4">
                    <?php include ('partials/sidebar-latest-posts.php') ?>
                </div>
            </div>

            <!-- Widget Random Post: sticky via JS -->
            <!-- Placeholder menjaga ruang saat widget di-fixed -->
            <div id="random-post-placeholder"></div>
            <div id="sidebar-random-post">
                <?php include ('partials/random-post.php') ?>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/css/share-buttons.css">
<style>
.sidebar-col {
    position: relative;
}
.sidebar-wrapper {
    display: block;
}
#random-post-placeholder {
    display: none;
}
#sidebar-random-post.rp-fixed {
    position: fixed;
    z-index: 99;
    box-sizing: border-box;
}
#sidebar-random-post.rp-absolute {
    position: absolute;
    z-index: 99;
    box-sizing: border-box;
}
@media (max-width: 991.98px) {
    #sidebar-random-post.rp-fixed,
    #sidebar-random-post.rp-absolute {
        position: static !important;
        width: auto !important;
        top: auto !important;
        left: auto !important;
    }
    #random-post-placeholder { display: none !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => { showCopiedMessage(); });
        } else {
            var input = document.createElement('input');
            input.style.position = 'absolute';
            input.style.left = '-9999px';
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showCopiedMessage();
        }
    }
    function showCopiedMessage() {
        var el = document.getElementById('linkCopied');
        el.style.display = 'inline';
        setTimeout(function() { el.style.display = 'none'; }, 2000);
    }

    /*
     * Sticky Random Post — Fixed + Absolute Switch
     *
     * State 1 "static"   : normal flow
     * State 2 "fixed"    : position:fixed, ikuti viewport
     * State 3 "absolute" : position:absolute relatif .sidebar-col, FREEZE
     *
     * setAbsolute() menghitung topRelative dari koordinat ABSOLUT dokumen:
     *   footerTopAbs    = scrollY + footer.getBoundingClientRect().top
     *   widgetTopAbs    = footerTopAbs - widgetHeight
     *   containerTopAbs = scrollY + sidebarCol.getBoundingClientRect().top
     *   topRelative     = widgetTopAbs - containerTopAbs
     *
     * Nilai topRelative adalah koordinat dokumen yang stabil —
     * tidak berubah saat scroll, tidak perlu update, tidak jitter, tidak overlap.
     *
     * leftRelative dihitung dari viewport coords (scrollY cancel):
     *   leftRelative = widget.getBoundingClientRect().left
     *                - sidebarCol.getBoundingClientRect().left
     * Ini kompensasi Bootstrap gutter (padding-left:15px) agar tidak geser.
     *
     * Collision check (posisi absolut):
     *   widgetBottomIfFixed = topOffset + widgetHeight
     *   footerTopViewport   = footer.getBoundingClientRect().top
     *   collision jika widgetBottomIfFixed >= footerTopViewport
     */
    (function () {
        var DESKTOP_BP = 992;
        var GAP        = 8;

        var widget      = document.getElementById('sidebar-random-post');
        var placeholder = document.getElementById('random-post-placeholder');
        var sidebarCol  = document.querySelector('.sidebar-col');
        var navbar      = document.querySelector('.modern-navigation');
        var footer      = document.querySelector('footer');

        if (!widget || !placeholder || !sidebarCol || !navbar || !footer) return;
        if (window.innerWidth < DESKTOP_BP) return;

        var state       = 'static';
        var naturalRect = null;
        var rafPending  = false;

        function getNavH() {
            return navbar.getBoundingClientRect().height;
        }

        function cacheNaturalRect() {
            var r = widget.getBoundingClientRect();
            naturalRect = {
                topAbs : r.top + window.scrollY,
                left   : r.left,
                width  : widget.offsetWidth,
                height : widget.offsetHeight
            };
        }

        function setStatic() {
            if (state === 'static') return;
            state = 'static';
            widget.classList.remove('rp-fixed', 'rp-absolute');
            widget.style.cssText = '';
            placeholder.style.display = 'none';
            placeholder.style.height  = '0';
            cacheNaturalRect();
        }

        function setFixed(topPx) {
            if (state === 'fixed') {
                widget.style.top = topPx + 'px';
                return;
            }
            state = 'fixed';
            placeholder.style.display = 'block';
            placeholder.style.height  = naturalRect.height + 'px';
            widget.classList.remove('rp-absolute');
            widget.classList.add('rp-fixed');
            widget.style.top   = topPx + 'px';
            widget.style.left  = naturalRect.left + 'px';
            widget.style.width = naturalRect.width + 'px';
        }

        function setAbsolute() {
            if (state === 'absolute') return;
            state = 'absolute';

            var scrollY = window.scrollY;

            /* Posisi absolut footer di dokumen */
            var footerTopAbs = scrollY + footer.getBoundingClientRect().top;

            /* Posisi absolut widget yang diinginkan: tepat di atas footer */
            var widgetTopAbs = footerTopAbs - naturalRect.height;

            /* Posisi absolut container di dokumen */
            var containerTopAbs = scrollY + sidebarCol.getBoundingClientRect().top;

            /* top relatif ke container — STABIL, tidak berubah saat scroll */
            var topRelative = widgetTopAbs - containerTopAbs;

            /* left relatif ke container — kompensasi Bootstrap gutter */
            var leftRelative = naturalRect.left - sidebarCol.getBoundingClientRect().left;

            placeholder.style.display = 'block';
            placeholder.style.height  = naturalRect.height + 'px';

            widget.classList.remove('rp-fixed');
            widget.classList.add('rp-absolute');
            widget.style.top   = topRelative  + 'px';
            widget.style.left  = leftRelative + 'px';
            widget.style.width = naturalRect.width + 'px';
        }

        function update() {
            if (window.innerWidth < DESKTOP_BP) { setStatic(); return; }
            if (!naturalRect) cacheNaturalRect();

            var scrollY   = window.scrollY;
            var navH      = getNavH();
            var topOffset = navH + GAP;
            var triggerAt = naturalRect.topAbs - topOffset;

            if (scrollY < triggerAt) {
                setStatic();
                return;
            }

            var footerTopViewport   = footer.getBoundingClientRect().top;
            var widgetBottomIfFixed = topOffset + naturalRect.height;

            if (widgetBottomIfFixed >= footerTopViewport) {
                setAbsolute();
            } else {
                if (state === 'absolute') {
                    /* Scroll ke atas: kembali ke fixed */
                    state = 'static';
                    widget.classList.remove('rp-absolute');
                    widget.style.cssText = '';
                    placeholder.style.display = 'none';
                    placeholder.style.height  = '0';
                    cacheNaturalRect();
                }
                setFixed(topOffset);
            }
        }

        function onScroll() {
            if (rafPending) return;
            rafPending = true;
            requestAnimationFrame(function () {
                rafPending = false;
                update();
            });
        }

        cacheNaturalRect();
        update();

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', function () {
            setStatic();
            if (window.innerWidth >= DESKTOP_BP) {
                cacheNaturalRect();
                update();
            }
        });
    })();

    function copyToClipboard(text) {
        var copiedBadge = document.getElementById('linkCopied');
        var successCallback = function() {
            if (copiedBadge) {
                copiedBadge.style.display = 'inline-block';
                setTimeout(function() {
                    copiedBadge.style.display = 'none';
                }, 2500);
            }
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(successCallback).catch(function() {
                fallbackCopy(text, successCallback);
            });
        } else {
            fallbackCopy(text, successCallback);
        }
    }

    function fallbackCopy(text, cb) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (cb) cb();
        } catch (err) {
            console.error('Copy failed', err);
        }
        document.body.removeChild(textArea);
    }
</script>
<?= $this->endSection() ?>