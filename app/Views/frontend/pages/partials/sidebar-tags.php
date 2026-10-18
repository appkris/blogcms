<div class="widget">
    <h2 class="section-title mb-3">Hastag</h2>
    <div class="widget-body">
        <ul class="widget-list">
            <?php
            foreach (get_all_tags() as $tag):
                if (empty(trim($tag))) continue; ?>
                <li><a href="<?= route_to('tag-post', trim($tag)) ?>">#<?= esc(trim($tag)) ?></a>
                </li>
                <?php
            endforeach ?>

        </ul>
    </div>
</div>