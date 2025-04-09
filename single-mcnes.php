<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article class="post mecene">

    <!-- Logo mécène et lien du site -->
    <?php
    $logo = get_field('logo_mecene');
    $site_link = get_field('lien_du_site');

    if ($logo) :
        $logo_url = is_int($logo) ? wp_get_attachment_image_url($logo, 'full') : $logo['url'];
    ?>
    <div class="mecene_logo container mb24">
        <?php if ($site_link) : ?>
        <a href="<?php echo esc_url($site_link); ?>" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo esc_url($logo_url); ?>" alt="Logo de <?php the_title(); ?>" />
        </a>
        <?php else : ?>
        <img src="<?php echo esc_url($logo_url); ?>" alt="Logo de <?php the_title(); ?>" />
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <h1 class="secondary-700 title_big mb40">
        <span class="para_big maj">Mécène SOLFA : </span>
        <?php the_title(); ?>
    </h1>

    <?php if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs" class="secondary-700 legend">', '</p>');
    } ?>

    <?php if (has_post_thumbnail()) : ?>
    <?php the_post_thumbnail('article_thumb', ['class' => 'featured_image']); ?>
    <?php endif; ?>

    <!-- Introduction du mécène -->
    <?php if (get_field('introduction_mecene')) : ?>
    <p class="introduction para_big primary-700 mb24"><?php echo esc_html(get_field('introduction_mecene')); ?></p>
    <?php endif; ?>

    <!-- Description du mécène -->
    <?php if (get_field('description_mecene')) : ?>
    <div class="description_mecene para primary-700 alignleft container">
        <?php the_field('description_mecene'); ?>
    </div>
    <?php endif; ?>

    <div class="like-section container">
        <div>
            <img src="<?php echo get_template_directory_uri(); ?>/img/like.svg" alt="" aria-hidden="true">
            <?php 
        $like_count = get_field('like_count') ?: 0;
        if ($like_count > 1) : ?>
            <p id="like-count" class="para_big ">
                <?php echo $like_count; ?> personnes fières <span class="para">de ce mécénat !</span>
            </p>
            <?php elseif ($like_count === 1) : ?>
            <p id="like-count" class="para_big ">
                1 personne fière <span class="para">de ce mécénat !</span>
            </p>
            <?php else : ?>
            <p id="like-count" class="para_big ">
                Aucun vote <span class="para">Partagez cette page pour plus de votes !</span>
            </p>
            <?php endif; ?>
        </div>
        <button id="like-button" role="button"
            aria-pressed="<?php echo isset($_COOKIE['liked_' . get_the_ID()]) ? 'true' : 'false'; ?>"
            class="button primary <?php echo isset($_COOKIE['liked_' . get_the_ID()]) ? 'disabled' : ''; ?>"
            <?php echo isset($_COOKIE['liked_' . get_the_ID()]) ? 'disabled' : ''; ?>>
            <?php echo isset($_COOKIE['liked_' . get_the_ID()]) ? "Like déjà enregistré" : "J'ajoute mon like !"; ?>
        </button>
    </div>



    <!-- Informations sur le représentant·e -->
    <?php if (get_field('nom_contact_mecene') || get_field('photo_contact_mecene') || get_field('profil_linkedin_mecene') || get_field('temoignage_mecene')) : ?>
    <div class="representant">

        <!-- Nom et fonction -->
        <?php if (get_field('nom_contact_mecene')) : ?>
        <h2 class="nom_contact primary-500 title_lit maj"><?php echo esc_html(get_field('nom_contact_mecene')); ?></h2>
        <?php endif; ?>

        <div class="bloc_photo_represent">

            <!-- Photo de profil au format mecene_large -->
            <?php
            $photo_contact_id = get_field('photo_contact_mecene');
            if( $photo_contact_id ):
                echo wp_get_attachment_image( $photo_contact_id, 'mecene_large', false, array(
                    'alt' => 'Photo de ' . esc_attr(get_field('nom_contact_mecene'))
                ) );
            endif;
            ?>

            <!-- Lien vers le profil LinkedIn avec icône -->
            <?php if (get_field('profil_linkedin_mecene')) : ?>
            <a href="<?php echo esc_url(get_field('profil_linkedin_mecene')); ?>" target="_blank"
                rel="noopener noreferrer" title="LinkedIn de <?php echo esc_attr(get_field('nom_contact_mecene')); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/img/LinkedIn_icon.svg" alt=""
                    class="linkedin_icon">
            </a>
            <?php endif; ?>

        </div>

        <!-- Témoignage -->
        <?php if (get_field('temoignage_mecene')) : ?>
        <div class="temoignage_mecene para primary-700 alignleft">
            <?php the_field('temoignage_mecene'); ?>
        </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

    <!-- Lien vers la page d'engagement RSE de l'entreprise -->
    <?php if (get_field('lien_rse_entreprise')) : ?>
    <a href="<?php echo esc_url(get_field('lien_rse_entreprise')); ?>" target="_blank" rel="noopener noreferrer"
        class="button primary">
        Découvrir leur politique RSE
    </a>
    <?php endif; ?>

</article>
<?php endwhile; endif; ?>

<!-- Charger jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Charger like.js et ajouter une configuration pour AJAX -->
<script>
const likeData = {
    ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
    post_id: "<?php echo get_the_ID(); ?>"
};
</script>
<script src="<?php echo get_template_directory_uri(); ?>/like.js"></script>

<?php get_footer(); ?>