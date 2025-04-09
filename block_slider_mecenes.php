<?php
$args = array(
    'post_type' => 'Mécènes',
    'post_status' => 'publish',
    'posts_per_page' => 6,
);
$mecenes = new WP_Query($args);

if ($mecenes->have_posts()) : ?>
<div class="mecenes container slider full_width">
    <h2 class="sr_only">Nos mécènes :</h2>
    <ul class="container gap8">
        <?php while ($mecenes->have_posts()) : $mecenes->the_post(); 
            if (get_field('end_campagne')) {
                continue;
            }
            
            $thumbnail = get_field('logo_mecene');
            $title = get_the_title();

            $thumbnail_url = is_int($thumbnail) ? wp_get_attachment_image_url($thumbnail, 'mecene_thumb') : $thumbnail['url'];
            ?>

        <li class="container vertical pad24 gap24">
            <?php
                if ($thumbnail_url) {
                    echo '<img src="' . esc_url($thumbnail_url) . '" alt="">';
                }
                ?>
            <div class="mecenes_info gap24 container">
                <h3 class="title_med secondary-700"><?php echo esc_html($title); ?></h3>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="button secondary">En savoir plus</a>
            </div>
        </li>
        <?php endwhile; ?>
    </ul>

    <?php wp_reset_postdata(); ?>
    <p class="ui-color-white para_lit container pad32 view_articles">
        <a href="<?php echo home_url('/mecenes'); ?>">Voir tous les mécènes</a>
    </p>
</div>
<?php endif; ?>