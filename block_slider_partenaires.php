<?php
// Récupérer les posts du custom post type "Partenaires"
$args = array(
    'post_type' => 'Partenaires',
    'post_status' => 'publish',
    'posts_per_page' => -1, // Pas de limite
);
$partenaires = new WP_Query($args);

if ($partenaires->have_posts()) : ?>
<div class="partenaires_block slider container mt-24 full_width">
    <div class="container gap4 partenaires_titre">
        <h2 class="title_med primary-900 maj">FINANCEURS ET PARTENAIRES PUBLICS</h2>
        <p class="title_lit primary-900">présents à nos côtés</p>
    </div>
    <ul class="container gap8">
        <?php while ($partenaires->have_posts()) : $partenaires->the_post(); ?>
        <li>
            <?php
            // Récupérer l'URL du site et le logo ACF
            $site_url = get_field('lien_du_site');
            $logo = get_field('logo_partenaire');
            $title = get_the_title();

            // Vérifier si le logo est un ID et récupérer l'URL si nécessaire
            $logo_url = is_int($logo) ? wp_get_attachment_image_url($logo, 'partenaire_card_thumb') : $logo['url'];
            ?>
            <a href="<?php echo esc_url($site_url); ?>" class="container vertical pad24 gap24 aligncenter"
                rel="external" title="Aller vers le site du partenaire <?php echo esc_attr($title); ?>">
                <?php if ($logo_url) : ?>
                <img src="<?php echo esc_url($logo_url); ?>" alt="Logo de <?php echo esc_attr($title); ?>">
                <?php endif; ?>
                <h4 class="title_med maj primary-900"><?php echo esc_html($title); ?></h4>
            </a>
        </li>
        <?php endwhile; ?>
    </ul>

    <?php wp_reset_postdata(); ?>
</div>
<?php endif; ?>