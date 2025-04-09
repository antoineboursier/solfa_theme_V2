<?php

/******************************************************/
/************** CSS ET JS POUR MODIF BACK *************/
/******************************************************/

function enqueue_custom_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('editor-styles.css');
    wp_enqueue_style(
        'custom-editor-styles',
        get_stylesheet_directory_uri() . '/editor-styles.css',
        array(),
        filemtime(get_stylesheet_directory() . '/editor-styles.css')
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_custom_editor_styles');

/******************************************************/
/********************** COULEURS **********************/
/******************************************************/

function custom_gutenberg_colors() {
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary 100', 'textdomain'),
            'slug'  => 'primary-100',
            'color' => 'rgba(242, 237, 247, 1)',
        ),
        array(
            'name'  => __('Primary 300', 'textdomain'),
            'slug'  => 'primary-300',
            'color' => 'rgba(197, 163, 236, 1)',
        ),
        array(
            'name'  => __('Primary 500', 'textdomain'),
            'slug'  => 'primary-500',
            'color' => 'rgba(132, 84, 189, 1)',
        ),
        array(
            'name'  => __('Primary 700', 'textdomain'),
            'slug'  => 'primary-700',
            'color' => 'rgba(95, 64, 131, 1)',
        ),
        array(
            'name'  => __('Primary 900', 'textdomain'),
            'slug'  => 'primary-900',
            'color' => 'rgba(51, 19, 89, 1)',
        ),
        array(
            'name'  => __('Secondary 100', 'textdomain'),
            'slug'  => 'secondary-100',
            'color' => 'rgba(224, 241, 235, 1)',
        ),
        array(
            'name'  => __('Secondary 300', 'textdomain'),
            'slug'  => 'secondary-300',
            'color' => 'rgba(151, 236, 205, 1)',
        ),
        array(
            'name'  => __('Secondary 500', 'textdomain'),
            'slug'  => 'secondary-500',
            'color' => 'rgba(53, 188, 139, 1)',
        ),
        array(
            'name'  => __('Secondary 700', 'textdomain'),
            'slug'  => 'secondary-700',
            'color' => 'rgba(9, 120, 105, 1)',
        ),
        array(
            'name'  => __('Secondary 900', 'textdomain'),
            'slug'  => 'secondary-900',
            'color' => 'rgba(7, 92, 81, 1)',
        ),
        array(
            'name'  => __('Tertiary 100', 'textdomain'),
            'slug'  => 'tertiary-100',
            'color' => 'rgba(219, 239, 255, 1)',
        ),
        array(
            'name'  => __('Tertiary 300', 'textdomain'),
            'slug'  => 'tertiary-300',
            'color' => 'rgba(134, 193, 242, 1)',
        ),
        array(
            'name'  => __('Tertiary 500', 'textdomain'),
            'slug'  => 'tertiary-500',
            'color' => 'rgba(59, 143, 212, 1)',
        ),
        array(
            'name'  => __('Tertiary 700', 'textdomain'),
            'slug'  => 'tertiary-700',
            'color' => 'rgba(27, 110, 182, 1)',
        ),
        array(
            'name'  => __('Tertiary 900', 'textdomain'),
            'slug'  => 'tertiary-900',
            'color' => 'rgba(21, 81, 130, 1)',
        ),
        array(
            'name'  => __('White', 'textdomain'),
            'slug'  => 'ui-color-white',
            'color' => 'rgba(255, 255, 255, 1)',
        ),
        array(
            'name'  => __('White Ambient', 'textdomain'),
            'slug'  => 'ui-color-white-ambiant',
            'color' => 'rgba(250, 248, 245, 1)',
        ),
        array(
            'name'  => __('Grey', 'textdomain'),
            'slug'  => 'ui-color-grey',
            'color' => 'rgba(141, 141, 141, 1)',
        ),
        array(
            'name'  => __('Red Max', 'textdomain'),
            'slug'  => 'ui-color-redmax',
            'color' => 'rgba(193, 39, 95, 1)',
        ),
        array(
            'name'  => __('Red Min', 'textdomain'),
            'slug'  => 'ui-color-redmin',
            'color' => 'rgba(250, 238, 242, 1)',
        ),
    ));
}
add_action('after_setup_theme', 'custom_gutenberg_colors');


/****************** STYLE DE TITRES *****************/

function custom_gutenberg_text_styles() {
    register_block_style('core/paragraph', array(
        'name'  => 'title-big',
        'label' => __('Title Big', 'textdomain'),
    ));
    register_block_style('core/paragraph', array(
        'name'  => 'title-med',
        'label' => __('Title Medium', 'textdomain'),
    ));
    register_block_style('core/paragraph', array(
        'name'  => 'title-lit',
        'label' => __('Title Light', 'textdomain'),
    ));
}
add_action('init', 'custom_gutenberg_text_styles');

/****************** STYLE DE TEXTES (S,M,L,XL) *****************/

function custom_gutenberg_typography_sizes() {
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('S', 'textdomain'),
            'slug' => 'small',
            'size' => '0.6875rem' // Correspond à .legend
        ),
        array(
            'name' => __('M', 'textdomain'),
            'slug' => 'medium',
            'size' => '0.8125rem' // Correspond à .para_lit
        ),
        array(
            'name' => __('L', 'textdomain'),
            'slug' => 'large',
            'size' => '1rem' // Correspond à .para
        ),
        array(
            'name' => __('XL', 'textdomain'),
            'slug' => 'extra-large',
            'size' => '1.125rem' // Correspond à .para_big
        ),
    ));
}
add_action('after_setup_theme', 'custom_gutenberg_typography_sizes');

/******************************************************/
/****************** BOUTONS EN BACK *******************/
/******************************************************/

function custom_button_styles() {
    // Style "Primaire"
    register_block_style(
        'core/button',
        array(
            'name'  => 'primary',
            'label' => __('Primaire', 'textdomain'),
            'isDefault' => true,
        )
    );
    
    // Style "Secondaire"
    register_block_style(
        'core/button',
        array(
            'name'  => 'secondary',
            'label' => __('Secondaire', 'textdomain'),
        )
    );

    // Style "Tertiaire"
    register_block_style(
        'core/button',
        array(
            'name'  => 'tertiary',
            'label' => __('Tertiaire', 'textdomain'),
        )
    );
}
add_action('init', 'custom_button_styles');

?>