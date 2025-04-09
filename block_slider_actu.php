<?php
// Récupérer les IDs des articles sticky
$sticky_posts = get_option('sticky_posts');

// Limiter le nombre d'articles sticky à inclure
$sticky_args = array(
    'post__in'            => $sticky_posts,
    'posts_per_page'      => 10, // Limite maximale pour les articles sticky
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => 1,
);

$sticky_query = get_posts($sticky_args);

// Calculer combien d'articles normaux il reste à récupérer pour ne pas dépasser 10
$remaining_posts_count = max(10 - count($sticky_query), 0);

// Récupérer les articles normaux sans inclure ceux déjà dans sticky
$normal_args = array(
    'post__not_in'        => $sticky_posts,
    'posts_per_page'      => $remaining_posts_count,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => 1,
);

$normal_query = get_posts($normal_args);

// Fusionner les articles sticky et normaux pour affichage
$final_posts = array_merge($sticky_query, $normal_query);

if (!empty($final_posts)) : ?>
<div class="actu_block container slider full_width">
    <h2 class="sr_only">Nos articles d'actualités :</h2>
    <ul class="container gap8">
        <?php foreach ($final_posts as $post) : ?>
        <li class="<?php echo in_array($post->ID, $sticky_posts) ? 'sticky' : ''; ?>">
            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="container vertical">
                <?php
                if (has_post_thumbnail($post->ID)) {
                    echo get_the_post_thumbnail($post->ID, 'actu_card_thumb', array('alt' => get_the_title($post->ID)));
                }
                ?>
                <div class="actu_card_info pad24 container gap8">
                    <h3 class="title_med maj primary-100"><?php echo esc_html(get_the_title($post->ID)); ?></h3>

                    <p class="excerpt para_lit primary-300 mb8">
                        <?php echo esc_html(wp_trim_words(get_the_excerpt($post->ID), 20, '...')); ?>
                    </p>

                    <p class="meta legend primary-100">
                        <span class="date"><?php echo get_the_date('d.m.Y', $post->ID); ?></span> par
                        <span
                            class="author"><?php echo get_the_author_meta('display_name', $post->post_author); ?></span>
                    </p>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <p class="primary-700 para_lit view_articles">
        <a href="<?php echo home_url('/actualites'); ?>">Voir tous les articles</a>
    </p>

</div>
<?php endif; ?>