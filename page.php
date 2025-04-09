<?php get_header(); ?>

<?php

/*** SI ACCUEIL ***/

if (is_front_page()) :
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    endif;

/*** SINON : ***/

else :
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>

<?php if (has_post_thumbnail()) : ?>
<?php the_post_thumbnail('page_thumb', ['class' => 'featured_image']); ?>
<?php endif; ?>

<h1 class="secondary-700 title_big maj"><?php the_title(); ?></h1>

<?php if (function_exists('yoast_breadcrumb')) : ?>
<?php yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>'); ?>
<?php endif; ?>

<?php 
    $is_full_width = get_post_meta(get_the_ID(), '_full_width_content', true);
    $content_class = $is_full_width ? 'full_width' : 'alignleft';
    ?>

<div class="content <?php echo esc_attr($content_class); ?>">
    <?php the_content(); ?>
</div>

<?php
    // Charger le fichier de contenu dédié en fonction du slug
    $slug = get_post_field('post_name');
    $content_file = 'content_' . $slug . '.php';

    if (file_exists(get_template_directory() . '/' . $content_file)) {
        include get_template_directory() . '/' . $content_file;
    }
    ?>

<?php endwhile;
    endif;
endif;
?>

<?php get_footer(); ?>