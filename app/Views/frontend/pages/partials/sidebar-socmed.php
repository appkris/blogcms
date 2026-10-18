<div class="widget">
    <h2 class="section-title mb-3">Social Media</h2>
    <div class="widget-body">
        <ul class="widget-list">
            <ul class="widget-list">
                <?php $sm = get_social_media(); ?>
                <div class="text-center mt-2 social-icons-simple">
                    <?php if (!empty($sm->facebook_url)): ?>
                        <a href="<?= esc($sm->facebook_url) ?>" target="_blank" class="social-icon facebook" title="Facebook">
                            <img loading="lazy" decoding="async" src="/images/social/1.png" alt="facebook">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->twitter_url)): ?>
                        <a href="<?= esc($sm->twitter_url) ?>" target="_blank" class="social-icon twitter" title="Twitter">
                            <img loading="lazy" decoding="async" src="/images/social/2.png" alt="twitter">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->instagram_url)): ?>
                        <a href="<?= esc($sm->instagram_url) ?>" target="_blank" class="social-icon instagram" title="Instagram">
                            <img loading="lazy" decoding="async" src="/images/social/3.png" alt="instagram">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->youtube_url)): ?>
                        <a href="<?= esc($sm->youtube_url) ?>" target="_blank" class="social-icon youtube" title="YouTube">
                            <img loading="lazy" decoding="async" src="/images/social/4.png" alt="youtube">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->tiktok_url)): ?>
                        <a href="<?= esc($sm->tiktok_url) ?>" target="_blank" class="social-icon tiktok" title="TikTok">
                            <img loading="lazy" decoding="async" src="/images/social/5.png" alt="tiktok">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($sm->linkedin_url)): ?>
                        <a href="<?= esc($sm->linkedin_url) ?>" target="_blank" class="social-icon linkedin" title="LinkedIn">
                            <img loading="lazy" decoding="async" src="/images/social/6.png" alt="linkedin">
                        </a>
                    <?php endif; ?>
                </div>
            </ul>
        </ul>
    </div>
</div>