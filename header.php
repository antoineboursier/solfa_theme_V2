<!doctype html>

<html <?php language_attributes(); ?>>

<head>

    <title>
        <?php echo wp_get_document_title(); ?>
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=yes">
    <link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/variables.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700&family=Poppins:wght@400;700&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link
            href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700&family=Poppins:wght@500;700&display=swap"
            rel="stylesheet">
    </noscript>
    <script src="<?php echo get_template_directory_uri(); ?>/script.js" defer></script>

    <link rel="apple-touch-icon" sizes="57x57"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo get_template_directory_uri(); ?>/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/img/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage"
        content="<?php echo get_template_directory_uri(); ?>/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>


</head>

<body <?php body_class(); ?>>

    <?php
    $current_url = home_url( add_query_arg( null, null ) );
    if ( strpos( $current_url, 'staging' ) !== false ) : ?>
    <div id="staging_banner" class="para_big" style="background-color: orange;
    color: var(--primary-900);
    text-align: center;
    padding: var(--spacing-24) var(--spacing-80);
    position: fixed;
    top: 0;
    left: 65%;
    z-index: 200;
    transform: translateX(-50%);
    border-radius: 0 0 40px 40px;">
        SITE STAGING
    </div>
    <?php endif; ?>

    <header>
        <div class="content_header max1280">
            <button id="burger-menu" aria-expanded="false" aria-controls="main-nav">
                <span class="sr_only">Menu</span>
                <div aria-hidden="true" class="burger-bar"></div>
                <div aria-hidden="true" class="burger-bar"></div>
                <div aria-hidden="true" class="burger-bar"></div>
            </button>

            <nav id="main-nav" aria-hidden="true" class="menu hidden">
                <button id="close-menu" class="close-menu" aria-label="Fermer le menu">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/icons/cross.svg" alt="" aria-hidden="true"
                        width="24" height="24">
                    <span class="sr_only">Fermer le menu</span>
                </button>
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu_v2',
                        'container' => 'ul',
                        'menu_class' => '',
                        'fallback_cb' => false
                    ));
                ?>
            </nav>
            <nav id="desktop_nav" aria-hidden="true" class="para">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu_v2_desktop',
                        'container' => 'ul',
                        'menu_class' => '',
                        'fallback_cb' => false
                    ));
                ?>
            </nav>

            <a id="faire_un_don_cta" class="button primary"
                href="https://www.helloasso.com/associations/association-solfa/formulaires/5">
                <img src="<?php echo get_template_directory_uri(); ?>/img/icons/bubble.svg" alt="" aria-hidden="true"
                    width="24" height="24">
                Faire un don
            </a>
        </div>
    </header>

    <?php if (current_user_can('manage_options') && wp_is_mobile() === false) { ?>
    <a id="edit" class="primary button"
        href="<?php echo get_edit_post_link(); ?>"><?php _e("Modifier la page","ntp_framework"); ?></a>
    <?php } ?>

    <main role="main" class="para" id="<?php echo is_category() ? 'category_page' : get_post_field('post_name'); ?>">
        <section class="full_width">