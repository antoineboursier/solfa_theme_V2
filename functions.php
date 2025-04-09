<?php
	
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size('actu_card_thumb', 304, 448, true);
		add_image_size('partenaire_card_thumb', 160, 144, false);
		add_image_size('appel_don_thumb', 573, 399, true);
		add_image_size('mecene_thumb', 100, 100, false);
		add_image_size('mecene_large', 400, 400, false);
		add_image_size('article_thumb', 560, 800, false);
		add_image_size('page_thumb', 600, 224, true);
	};	

	add_theme_support('post-thumbnails');
	
	register_nav_menus(array(
	'menu_v2' => 'Menu V2',
	'menu_v2_desktop' => 'Menu V2 Desktop',
	'footer_link' => 'Liens du footer'
	));	

	require get_template_directory() . '/functions_backoffice.php';

/******************************************************/
/******************** CUSTOM POST ********************/
/******************************************************/

function create_custom_post_type($type, $labels, $menu_position, $menu_icon, $supports = array('title', 'thumbnail'), $capability_type = 'post', $template = null, $slug = '') {
    $args = array(
        'labels'              => $labels,
        'supports'            => $supports,
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => $menu_position,
        'can_export'          => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => $slug ?: $type), // Utiliser $slug ou $type si $slug est vide
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => $capability_type,
        'menu_icon'           => $menu_icon,
        'show_in_rest'        => ($type === 'hebergements'), // Activer Gutenberg seulement pour "Hébergements"
        'template'            => ($type === 'hebergements') ? $template : null // Appliquer le modèle uniquement à "Hébergements"
    );
    register_post_type($type, $args);
}

function register_custom_post_types() {
    // Partenaires
    $labels_partenaires = array(
        'menu_name' => __( 'Partenaires publiques' ),
        'name' => __( 'Partenaires' ),
        'singular_name' => __( 'Partenaire' ),
        'name_admin_bar' => __( 'Ajouter un partenaire' ),
        'all_items' => __( 'Voir tous les partenaires' ),
        'add_new' => __( 'Ajouter un partenaire' ),
        'add_new_item' => __( 'Ajouter un partenaire' ),
        'edit_item' => __( 'Modifier ce partenaire' )
    );
    create_custom_post_type('Partenaires', $labels_partenaires, 6, 'dashicons-businessperson');

    // Appels aux dons
    $labels_appels = array(
        'menu_name' => __( 'Projets' ),
        'name' => __( 'Projets' ),
        'singular_name' => __( 'Projet' ),
        'name_admin_bar' => __( 'Ajouter un projet' ),
        'all_items' => __( 'Voir tous les projets' ),
        'add_new' => __( 'Ajouter un projet' ),
        'add_new_item' => __( 'Ajouter un projet' ),
        'edit_item' => __( 'Modifier ce projet' )
    );
    create_custom_post_type('Appels', $labels_appels, 5, 'dashicons-heart');

    // Mécènes
    $labels_mecenes = array(
        'menu_name' => __( 'Mécènes' ),
        'name' => __( 'Mécènes' ),
        'singular_name' => __( 'Mécène' ),
        'name_admin_bar' => __( 'Ajouter un mécène' ),
        'all_items' => __( 'Voir tous les mécènes' ),
        'add_new' => __( 'Ajouter un mécène' ),
        'add_new_item' => __( 'Ajouter un mécène' ),
        'edit_item' => __( 'Modifier ce mécène' )
    );
    create_custom_post_type('Mécènes', $labels_mecenes, 7, 'dashicons-businessman');

    // Hébergements avec support Gutenberg
    $labels_hebergements = array(
        'menu_name' => __( 'Hébergements' ),
        'name' => __( 'Hébergements' ),
        'singular_name' => __( 'Hébergement' ),
        'name_admin_bar' => __( 'Ajouter un hébergement' ),
        'all_items' => __( 'Voir tous les hébergements' ),
        'add_new' => __( 'Ajouter un hébergement' ),
        'add_new_item' => __( 'Ajouter un hébergement' ),
        'edit_item' => __( 'Modifier cet hébergement' ),
        'new_item' => __( 'Nouvel hébergement' ),
        'view_item' => __( 'Voir l\'hébergement' ),
        'search_items' => __( 'Rechercher des hébergements' ),
        'not_found' => __( 'Aucun hébergement trouvé' ),
        'not_found_in_trash' => __( 'Aucun hébergement dans la corbeille' ),
    );

    // Modèle pour le post type Hébergements
    $hebergement_template = array(
        array('core/paragraph', array(
            'placeholder' => 'Ajouter une description pour cet hébergement...'
        )),
        array('core/image', array(
            'align' => 'center',
        )),
    );

    create_custom_post_type(
        'hebergements',
        $labels_hebergements,
        8,
        'dashicons-admin-home',
        array('title', 'thumbnail', 'editor', 'revisions', 'page-attributes'), // Supports de Gutenberg complet
        'page', // Capacité de type page pour activer le constructeur
        $hebergement_template // Modèle de blocs par défaut pour l'hébergement
    );
}

add_action( 'init', 'register_custom_post_types' );

// Activer les images à la une pour les types de posts personnalisés
add_theme_support( 'post-thumbnails', array('page', 'post', 'Partenaires', 'Appels', 'Mécènes', 'hebergements') );



/******************************************************/
/******* TAXONOMIE SPÉCIFIQUE AUX HÉBERGEMENTS ********/
/******************************************************/

function register_hebergement_taxonomy() {
    $labels = array(
        'name'                       => __( 'Villes de rattachement' ),
        'singular_name'              => __( 'Ville de rattachement' ),
        'search_items'               => __( 'Rechercher des villes' ),
        'all_items'                  => __( 'Toutes les villes' ),
        'parent_item'                => __( 'Ville parente' ),
        'parent_item_colon'          => __( 'Ville parente:' ),
        'edit_item'                  => __( 'Modifier la ville' ),
        'update_item'                => __( 'Mettre à jour la ville' ),
        'add_new_item'               => __( 'Ajouter une nouvelle ville' ),
        'new_item_name'              => __( 'Nom de la nouvelle ville' ),
        'menu_name'                  => __( 'Villes de rattachement' ),
    );

    register_taxonomy(
        'hebergement_category',
        'hebergements',
        array(
            'labels'            => $labels,
            'rewrite'           => array( 'slug' => 'categorie-hebergement' ),
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
        )
    );
}
add_action( 'init', 'register_hebergement_taxonomy' );

	

/******************************************************/
/******************** ACTIVER SVG *********************/
/******************************************************/

function enable_svg_upload($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'enable_svg_upload', 20);


/******************************************************/
/******************** SHORTSCODE **********************/
/******************************************************/
	
function render_slider_actu_shortcode() {
	ob_start();
	include get_template_directory() . '/block_slider_actu.php';
	return ob_get_clean();
}
add_shortcode('slider_actu', 'render_slider_actu_shortcode');

function render_slider_partenaire_shortcode() {
	ob_start();
	include get_template_directory() . '/block_slider_partenaires.php';
	return ob_get_clean();
}
add_shortcode('slider_partenaires', 'render_slider_partenaire_shortcode');

function render_slider_appel_don_shortcode() {
	ob_start();
	include get_template_directory() . '/block_slider_appel_don.php';
	return ob_get_clean();
}
add_shortcode('slider_appel_don', 'render_slider_appel_don_shortcode');

function render_slider_mecenes_shortcode() {
	ob_start();
	include get_template_directory() . '/block_slider_mecenes.php';
	return ob_get_clean();
}
add_shortcode('slider_mecenes', 'render_slider_mecenes_shortcode');

function render_abonnement_newsletter_shortcode() {
	ob_start();
	include get_template_directory() . '/block_abonnement_newsletter.php';
	return ob_get_clean();
}
add_shortcode('abonnement_newsletter', 'render_abonnement_newsletter_shortcode');


/******************************************************/
/******************* ALT par ALT="" *******************/
/******************************************************/

function add_aria_hidden_to_empty_alt_images($content) {
// Utiliser un regex pour cibler les balises <img> avec un `alt` vide ou absent
$content = preg_replace_callback(
	'/<img([^>]*?)>/',
	function ($matches) {
		$img_tag = $matches[0];
		
		// Vérifier si l'image a un `alt=""` ou pas d'attribut `alt`
		if (strpos($img_tag, 'alt=""') !== false || !preg_match('/alt=/', $img_tag)) {
			// Ajouter aria-hidden="true"
			if (strpos($img_tag, 'aria-hidden') === false) {
				$img_tag = str_replace('<img', '<img aria-hidden="true"', $img_tag);
			}
		}
		return $img_tag;
	},
	$content
);

return $content;
}
add_filter('the_content', 'add_aria_hidden_to_empty_alt_images');

/******************************************************/
/************ REL EXTERNAL SUR LES LIENS **************/
/******************************************************/

function add_external_rel_to_links($content) {
$home_url = get_home_url();
return preg_replace_callback(
	'/<a\s+([^>]*?)href=["\'](http[s]?:\/\/(?!' . preg_quote(parse_url($home_url, PHP_URL_HOST)) . ')[^"\']+)["\']([^>]*)>/i',
	function ($matches) {
		// Vérifier si 'rel' n'est pas déjà présent et que $matches[4] existe
		if (!isset($matches[4]) || strpos($matches[4], 'rel=') === false) {
			return '<a ' . $matches[1] . 'href="' . $matches[2] . '" rel="external"' . (isset($matches[4]) ? $matches[4] : '') . '>';
		}
		return $matches[0];
	},
	$content
);
}
add_filter('the_content', 'add_external_rel_to_links');

/******************************************************/
/************* CASE FULL WIDTH EN PAGES ***************/
/******************************************************/

function add_full_width_meta_box() {
add_meta_box(
	'full_width_meta_box',
	'Options de mise en page',
	'display_full_width_meta_box',
	'page',
	'side',
	'default'
);
}
add_action('add_meta_boxes', 'add_full_width_meta_box');

function display_full_width_meta_box($post) {
	$value = get_post_meta($post->ID, '_full_width_content', true);
	?>
<label for="full_width_content">
    <input type="checkbox" name="full_width_content" id="full_width_content" value="1" <?php checked($value, '1'); ?> />
    Contient du contenu pleine largeur
</label>
<?php
}

function save_full_width_meta_box($post_id) {
	if (array_key_exists('full_width_content', $_POST)) {
		update_post_meta($post_id, '_full_width_content', $_POST['full_width_content']);
	} else {
		delete_post_meta($post_id, '_full_width_content');
	}
}
add_action('save_post', 'save_full_width_meta_box');

/******************************************************/
/**************** COMPTEUR DE LIKE ********************/
/******************************************************/

// AJAX function to handle the like
// Les scirpts jquery et like sont chargés dans la page mécène
function handle_like() {
	$post_id = $_POST['post_id'];

	if (!$post_id || isset($_COOKIE['liked_' . $post_id])) {
		wp_send_json_error('Already liked or invalid request.');
	}

	// Get current likes, increment, and save
	$like_count = get_field('like_count', $post_id);
	$like_count = $like_count ? $like_count + 1 : 1;
	update_field('like_count', $like_count, $post_id);

	// Set a cookie to prevent duplicate likes
	setcookie('liked_' . $post_id, 'true', time() + 3600 * 24 * 7 * 8, '/'); // Cookie de 8 semaines

	wp_send_json_success($like_count);
}
add_action('wp_ajax_nopriv_handle_like', 'handle_like');
add_action('wp_ajax_handle_like', 'handle_like');

/******************************************************/
/********* ICON DE MENU AVEC CHAMP ACF ****************/
/******************************************************/

function add_acf_icons_to_menu_items($item_output, $item, $depth, $args) {
    $icon_id = get_field('icones_de_lentree', $item);
    if ($icon_id) {
        $icon_url = wp_get_attachment_image_url($icon_id, 'thumbnail');
        $icon_html = '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true" class="menu-icon" width="24" height="24"> ';
        $item_output = $icon_html . $item_output;
    }
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_acf_icons_to_menu_items', 10, 4);

/******************************************************/
/***************** ACTIVER STICKY *********************/
/******************************************************/

function enable_sticky_support() {
    add_post_type_support('post', 'sticky');
}
add_action('init', 'enable_sticky_support');

/******************************************************/
/****************** VIOLENTOMETRE *********************/
/******************************************************/

require get_template_directory() . '/violentometre/functions_violentometre.php';


	
?>