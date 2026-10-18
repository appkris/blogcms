<?= $this->extend('frontend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<?= $this->section('page_meta') ?>
<meta name="robots" content="index,follow" />
<meta name="title" content="<?= esc($page->title) ?> - <?= get_settings()->blog_title ?>" />
<meta name="description" content="<?= esc($page->title) ?>" />
<?= $this->endSection() ?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <h1 class="h2 mb-4"><?= esc($page->title) ?></h1>
            <hr>
            <div class="page-content mt-3">
                <?= $page->content ?>
            </div>
            <div class="mt-4 pt-3 border-top text-muted small">
                Terakhir diperbarui: <?= date('d M Y', strtotime($page->updated_at)) ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
