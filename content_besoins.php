<div class="content appel_don container">
    <ul class="container gap8">
        <?php
        // Configuration de la pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Nouvelle requête pour afficher les appels aux dons avec pagination
        $appels_query = new WP_Query(array(
            'post_type' => 'Appels',
            'posts_per_page' => 15,
            'paged' => $paged,
        ));

        if ($appels_query->have_posts()) :
            while ($appels_query->have_posts()) : $appels_query->the_post();

                // Vérification de la case "end_campagne" pour ajouter une classe conditionnelle
                $is_closed = get_field('end_campagne') ? 'appel_close' : '';
                $is_closed_text = get_field('end_campagne') ? '<span class="legend primary-700 container mb8">Campagne terminée</span>' : '';

                // Récupération des champs ACF
                $campaign_url = get_field('lien_campagne_helloasso');
                $thumbnail = get_field('image_appel_don');
                $description = get_field('description_de_la_campagne');

                // Gestion de l'image de la campagne
                $thumbnail_url = is_int($thumbnail) ? wp_get_attachment_image_url($thumbnail, 'appel_don_thumb') : $thumbnail['url'];
        ?>

        <li class="container vertical pad8 <?php echo $is_closed; ?>">
            <?php if ($thumbnail_url) : ?>
            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>">
            <?php endif; ?>

            <div class="appel_info pad16 container gap8">
                <h2 class="para_big primary-500">
                    <?php echo $is_closed_text; ?>
                    <?php the_title(); ?>
                </h2>

                <p class="excerpt para_lit primary-700 mb8">
                    <?php echo esc_html(wp_trim_words($description, 15, '...')); ?>
                </p>

                <div class="buttons_zone container vertical gap8">
                    <?php if (!$is_closed && $campaign_url) : ?>
                    <a href="<?php echo esc_url($campaign_url); ?>" class="button primary" target="_blank"
                        rel="noopener noreferrer">Faites un don</a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="button secondary">En savoir plus</a>
                </div>
            </div>
        </li>

        <?php endwhile; ?>
    </ul>

    <!-- Ajout de la pagination -->
    <div class="pagination para_big container gap8">
        <p class="sr_only">Aller à la page :</p>
        <?php
            echo paginate_links(array(
                'total' => $appels_query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
        ?>
    </div>

    <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>