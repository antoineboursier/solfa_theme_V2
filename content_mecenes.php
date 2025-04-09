<div class="content mecenes container full_width">
    <ul class="container gap8">
        <?php
        // Configuration de la pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Nouvelle requête pour afficher les mécènes avec pagination
        $mecenes_query = new WP_Query(array(
            'post_type' => 'Mécènes', // Type de post personnalisé pour les mécènes
            'posts_per_page' => 15,
            'paged' => $paged,
        ));

        if ($mecenes_query->have_posts()) :
            while ($mecenes_query->have_posts()) : $mecenes_query->the_post();

                // Récupérer l'image et le titre du mécène
                $thumbnail = get_field('logo_mecene');
                $title = get_the_title();
                $thumbnail_url = is_int($thumbnail) ? wp_get_attachment_image_url($thumbnail, 'mecene_large') : $thumbnail['url'];
                ?>

        <li class="container vertical pad24 gap24">
            <?php if ($thumbnail_url) : ?>
            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Logo de <?php echo esc_attr($title); ?>">
            <?php endif; ?>
            <div class="mecenes_info gap24 container">
                <h3 class="title_big maj secondary-700"><?php echo esc_html($title); ?></h3>
                <?php if (get_field('introduction_mecene')) : ?>
                <p class="introduction para secondary-700 mb8">
                    <?php echo esc_html(get_field('introduction_mecene')); ?></p>
                <?php endif; ?>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="button secondary">En savoir plus</a>
            </div>
        </li>

        <?php endwhile; ?>
    </ul>

    <!-- Ajout de la pagination -->
    <div class="pagination para_big container gap8">
        <p class="sr_only">Aller à la page :</p>
        <?php
            echo paginate_links(array(
                'total' => $mecenes_query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('&laquo; Précédent'),
                'next_text' => __('Suivant &raquo;'),
            ));
        ?>
    </div>

    <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>