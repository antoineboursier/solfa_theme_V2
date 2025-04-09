<?php
/*
Template Name: Page Hébergements
*/
get_header();
?>

<?php if (function_exists('yoast_breadcrumb')) : ?>
<?php yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>'); ?>
<?php endif; ?>

<h1 class="secondary-700 title_big maj"><?php the_title(); ?></h1>


<div class="content full_width">

    <?php
    $categories = get_terms(array(
        'taxonomy' => 'hebergement_category',
        'hide_empty' => false,
    ));

    if (!empty($categories) && !is_wp_error($categories)) :
        foreach ($categories as $category) :
            $thumbnail = get_field('thumbnail_cat_hebergement', $category);
            $thumbnail_url = (!empty($thumbnail) && is_int($thumbnail)) ? wp_get_attachment_image_url($thumbnail, 'mecene_large') : (!empty($thumbnail['url']) ? $thumbnail['url'] : null);
    
            $post_count = $category->count;
            $category_link = get_term_link($category);
            ?>

    <div class="hbg_category_card">
        <?php if ($thumbnail_url) : ?>
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="" />
        <?php endif; ?>
        <h2 class="title_med maj secondary-500"><?php echo esc_html($category->name); ?></h2>

        <p class="para secondary-700">
            <?php
            if ($post_count > 1) {
                echo esc_html($post_count) . ' adresses';
            } elseif ($post_count === 1) {
                echo '1 adresse';
            } else {
                echo 'Aucune adresse';
            }
            ?>
        </p>

        <a href="<?php echo esc_url($category_link); ?>" class="button primary">Tout voir</a>
    </div>

    <?php
        endforeach;
    endif;
    ?>

</div>

<?php get_footer(); ?>