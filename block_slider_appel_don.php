<?php
$args = array(
    'post_type' => 'Appels',
    'post_status' => 'publish',
    'posts_per_page' => -1,
);
$appels = new WP_Query($args);

if ($appels->have_posts()) : ?>
<div class="appel_don container slider full_width">
    <h2 class="sr_only">Nos projets d'appel aux dons :</h2>
    <ul class="container gap8">
        <?php while ($appels->have_posts()) : $appels->the_post(); 
            if (get_field('end_campagne')) {
                continue;
            }
            
            $campaign_url = get_field('lien_campagne_helloasso');
            $thumbnail = get_field('image_appel_don');
            $description = get_field('description_de_la_campagne');
            
            $thumbnail_url = is_int($thumbnail) ? wp_get_attachment_image_url($thumbnail, 'appel_don_thumb') : $thumbnail['url'];
            ?>

        <li class="container vertical pad8">
            <?php
                if ($thumbnail_url) {
                    echo '<img src="' . esc_url($thumbnail_url) . '" alt="">';
                }
                ?>
            <div class="appel_info pad16 container">
                <p class="appel_don excerpt primary-500">
                    <?php echo esc_html(wp_trim_words($description, 16, '...')); ?>
                </p>
                <div class="buttons_zone container vertical gap8">
                    <?php if ($campaign_url) : ?>
                    <a href="<?php echo esc_url($campaign_url); ?>" class="button primary" target="_blank"
                        rel="noopener noreferrer">Faites un don</a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="button secondary">En savoir plus</a>
                </div>
            </div>
        </li>
        <?php endwhile; ?>
    </ul>

    <?php wp_reset_postdata(); ?>
    <p class="primary-700 para_lit container pad32 view_articles">
        <a href="<?php echo home_url('/besoins'); ?>">Voir tous les projets en financements</a>
    </p>
</div>
<?php endif; ?>