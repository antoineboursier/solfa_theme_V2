<?php get_header(); ?>

<h1 class="secondary-700 title_big maj">Catégorie : <?php single_cat_title(); ?></h1>

<?php if (function_exists('yoast_breadcrumb')) : ?>
<?php yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>'); ?>
<?php endif; ?>

<div class="content actu_block container">

    <!-- Description de la catégorie si disponible -->
    <?php if (category_description()) : ?>
    <div class="category-description">
        <?php echo category_description(); ?>
    </div>
    <?php endif; ?>

    <!-- Bloc de catégories disponibles -->
    <div id="categorie_block" class="container vertical gap24">
        <p class="primary-500 legend">Toutes les catégories :</p>
        <div class="category_list container gap8">
            <?php
        $current_category_id = get_queried_object_id(); // ID de la catégorie en cours
        $all_categories = get_categories();
        foreach ($all_categories as $category) :
            $category_link = get_category_link($category->term_id);
            $category_slug = esc_attr($category->slug);
            // Vérifier si la catégorie est la catégorie en cours
            $is_current_cat = $category->term_id === $current_category_id ? 'current_cat' : '';
        ?>
            <a href="<?php echo esc_url($category_link); ?>"
                class="category_button <?php echo $category_slug; ?> <?php echo $is_current_cat; ?>">
                <?php echo esc_html($category->name); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>


    <ul class="container gap8">
        <?php
        // Configuration de la pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Boucle de base pour afficher les articles de la catégorie en cours
        if (have_posts()) :
            while (have_posts()) : the_post(); ?>

        <li>
            <a href="<?php echo esc_url(get_permalink()); ?>" class="container vertical">
                <?php if (has_post_thumbnail()) : ?>
                <?php echo get_the_post_thumbnail(get_the_ID(), 'actu_card_thumb', array('alt' => get_the_title())); ?>
                <?php endif; ?>

                <div class="actu_card_info pad24 container gap8">
                    <h3 class="title_med maj primary-100"><?php the_title(); ?></h3>

                    <p class="excerpt para_lit primary-300 mb8">
                        <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20, '...')); ?>
                    </p>

                    <p class="meta legend primary-100">
                        <span class="date"><?php echo get_the_date('d.m.Y'); ?></span> par
                        <span class="author"><?php echo get_the_author(); ?></span>
                    </p>
                </div>
            </a>
        </li>

        <?php endwhile; ?>
    </ul>

    <!-- Ajout de la pagination -->
    <div class="pagination para_big container gap8">
        <p class="sr_only">Aller à la page :</p>
        <?php
            echo paginate_links(array(
                'total' => $wp_query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
        ?>
    </div>

    <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>