<?php get_header(); ?>

<?php if (function_exists('yoast_breadcrumb')) : ?>
<?php yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>'); ?>
<?php endif; ?>

<a href="<?php echo home_url('/nous-trouver'); ?>" class="backlink">
    <img src="<?php echo get_template_directory_uri(); ?>/img/icons/back.svg" alt="">
    Retour à la liste des villes
</a>

<h1 class="secondary-700 title_big mb40 maj">
    <span class="para_big">Ville : </span>
    <?php single_term_title(); ?>
</h1>

<div class="content hebergement_category_list">
    <?php
    $current_term = get_queried_object();
    $args = array(
        'post_type' => 'hebergements',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'hebergement_category',
                'field'    => 'term_id',
                'terms'    => $current_term->term_id,
            ),
        ),
    );

    $hebergement_query = new WP_Query($args);

    if ($hebergement_query->have_posts()) : ?>
    <ul class="hebergements-grid container gap24">
        <?php while ($hebergement_query->have_posts()) : $hebergement_query->the_post(); ?>
        <li class="hebergement-item container vertical pad24">
            <?php
                    // Priorité à l'image mise en avant (thumbnail)
                    if (has_post_thumbnail()) :
                        the_post_thumbnail('page_thumb', ['class' => 'hebergement-thumbnail']);
                    else :
                        // Sinon, utiliser le champ personnalisé ACF
                        $thumbnail = get_field('image_appel_don');
                        if ($thumbnail) :
                            echo wp_get_attachment_image($thumbnail, 'page_thumb', false, ['class' => 'hebergement-thumbnail']);
                        endif;
                    endif;
                    ?>
            <h2 class="title_med primary-500"><?php the_title(); ?></h2>
            <a href="<?php the_permalink(); ?>" class="button secondary">Voir le détail</a>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php else : ?>
    <p>Aucun hébergement trouvé dans cette catégorie.</p>
    <?php endif;

    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>