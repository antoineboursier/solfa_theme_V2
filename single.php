<?php get_header();?>

<?php
if (have_posts()) :
    while (have_posts()) : the_post(); ?>
<article class="post">
    <?php if ( function_exists('yoast_breadcrumb') ) {   yoast_breadcrumb( '<p id="breadcrumbs" class="secondary-700 legend">','</p>' );
    } ?>
    <?php if (has_post_thumbnail()) : ?>
    <?php the_post_thumbnail('article_thumb', ['class' => 'featured_image']); ?>
    <?php endif; ?>

    <h1 class="primary-700 title_big"><?php the_title(); ?></h1>

    <p class="article_meta primary-700 legend">
        Publié le <?php echo get_the_date('d.m.Y'); ?> par <?php the_author(); ?>
    </p>

    <div class="content para primary-500 alignleft">
        <?php the_content(); ?>
    </div>
</article>
<?php endwhile;
endif;
?>

<?php if (has_category()) : ?>
<div id="categorie_block" class="container vertical gap24">
    <p class="primary-500 legend">Catégories liées à cet article :</p>
    <div class="category_list container gap8">
        <?php
        $categories = get_the_category();
        foreach ($categories as $category) :
            $category_link = get_category_link($category->term_id);
            $category_slug = esc_attr($category->slug); // Récupération du slug pour utilisation comme classe
        ?>
        <a href="<?php echo esc_url($category_link); ?>" class="category_button <?php echo $category_slug; ?>">
            <?php echo esc_html($category->name); ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<?php get_footer();?>