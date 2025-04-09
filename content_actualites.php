<div class="content actu_block container">

    <!-- Bloc de catégories disponibles -->
    <div id="categorie_block" class="container vertical gap24">
        <p class="primary-500 legend">Toutes les catégories :</p>
        <div class="category_list container gap8">
            <?php
            $all_categories = get_categories();
            foreach ($all_categories as $category) :
                $category_link = get_category_link($category->term_id);
                $category_slug = esc_attr($category->slug);
            ?>
            <a href="<?php echo esc_url($category_link); ?>" class="category_button <?php echo $category_slug; ?>">
                <?php echo esc_html($category->name); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <ul class="container gap8">
        <?php
        // Configuration de la pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Nouvelle requête pour afficher les articles avec pagination
        $actualites_query = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 15,
            'paged' => $paged,
        ));

        if ($actualites_query->have_posts()) :
            while ($actualites_query->have_posts()) : $actualites_query->the_post(); 
                $is_sticky = is_sticky(get_the_ID());
                ?>

        <li class="<?php echo $is_sticky ? 'sticky' : ''; ?>">
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
                'total' => $actualites_query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
        ?>
    </div>

    <?php wp_reset_postdata(); ?>
    <?php endif; ?>