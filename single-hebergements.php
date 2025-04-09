<?php get_header(); ?>

<?php 
$terms = get_the_terms(get_the_ID(), 'hebergement_category');
$term_name = $terms && !is_wp_error($terms) ? $terms[0]->name : '';
$term_link = $terms && !is_wp_error($terms) ? get_term_link($terms[0]) : '';
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article class="post hebergement">

    <?php if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>');
    } ?>

    <a href="<?php echo esc_url($term_link ?: home_url('/nous-trouver')); ?>" class="backlink">
        <img src="<?php echo get_template_directory_uri(); ?>/img/icons/back.svg" alt="">
        Retour à <?php echo esc_html($term_name ?: 'la liste des villes'); ?>
    </a>

    <?php if (has_post_thumbnail()) : ?>
    <?php the_post_thumbnail('page_thumb', ['class' => 'featured_image']); ?>
    <?php endif; ?>

    <h1 class="secondary-700 title_big mb40 maj">
        <span class="para_big">Hébergement : </span>
        <?php the_title(); ?>
    </h1>

    <h2 class="tax_cat_link secondary-700 para mb40 maj">
        <?php if ($term_name) : ?>
        <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term_name); ?></a>
        <?php endif; ?>
    </h2>

    <div class="content">
        <?php the_content(); ?>
    </div>

</article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>