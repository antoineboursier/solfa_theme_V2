<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article class="post appels <?php echo get_field('end_campagne') ? 'appel_close' : ''; ?>">


    <?php if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>');
    } ?>

    <!-- Image de la campagne -->
    <?php
    $thumbnail = get_field('image_appel_don');
    if ($thumbnail) :
        echo wp_get_attachment_image($thumbnail, 'article_thumb', false, array('class' => 'featured_image'));
    endif;
    ?>

    <h1 class="primary-700 title_med mb40 maj">
        <span class="para_big">Appels aux dons : </span>
        <?php the_title(); ?>
    </h1>

    <?php if (get_field('end_campagne')) : ?>
    <h2 class="legend primary-700 mb24">Cette campagne est terminée</h2>
    <?php endif; ?>

    <!-- Description de l'appel -->
    <?php if (get_field('description_de_la_campagne')) : ?>
    <div class="description_campagne para primary-700 alignleft container">
        <?php the_field('description_de_la_campagne'); ?>
    </div>
    <?php endif; ?>

    <!-- Bouton de don si la campagne est active -->
    <?php
    $campaign_url = get_field('lien_campagne_helloasso');
    if (!get_field('end_campagne') && $campaign_url) : ?>
    <div class="buttons_zone container vertical gap8">
        <a href="<?php echo esc_url($campaign_url); ?>" class="button primary" target="_blank"
            rel="noopener noreferrer">
            Financer cette campagne !
        </a>
    </div>
    <?php endif; ?>

</article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>