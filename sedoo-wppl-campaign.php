<?php
/**
 * Plugin Name: Sedoo - Campagnes
 * Description: Plugin nécessaire sur un wordpress type campagne
 * Version: 0.0.1
 * Author: Nicolas Gruwe 
 * GitHub Plugin URI: sedoo/sedoo-wppl-campaign
 * GitHub Branch:     master
 */
 
include 'sedoo-wppl-posttypes.php'; // post types viewers & product
include 'sedoo-wppl-admin-param-page.php'; // admin parameters page
include 'sedoo-wppl-admin-page.php'; // admin campaign admin page


///////
// CREATE OR UPDATE A PRODUCT
///////
// Func to check if product already exist
function sedoo_campaign_the_slug_exists($slug, $post_type) {
	$args = array(
		'name'   => $slug,
		'post_type'   => $post_type,
		'post_status' => 'publish',
		'numberposts' => 1
	);
	$sedoo_campaign_product_exist = get_posts($args);
	if( $sedoo_campaign_product_exist ) {
		return $sedoo_campaign_product_exist[0]->ID;
	} else {
		return false;
	}
}


add_action('wp_ajax_sedoo_campaign_create_or_update_product', 'sedoo_campaign_create_or_update_product');
add_action('wp_ajax_nopriv_sedoo_campaign_create_or_update_product', 'sedoo_campaign_create_or_update_product');
function sedoo_campaign_create_or_update_product() {
	$name = $_POST['product']['name'];
	$slug = $_POST['product']['id'];
	if(sedoo_campaign_the_slug_exists($slug,'sedoo_camp_products') != false) { // check here if product exist or not
		// UPDATE THE PRODUCT
		$sedoo_campaign_product_id = sedoo_campaign_the_slug_exists($slug,'sedoo_camp_products');
	}
	else {
		// INSERT THE PRODUCT
		$sedoo_campaign_new_product = array(
			'post_title'    => wp_strip_all_tags( $name ),
			'post_name'		=> $slug,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'		=> 'sedoo_camp_products'
		);

		// Insert the post into the database
		$sedoo_campaign_product_id = wp_insert_post( $sedoo_campaign_new_product );
		// And add it into the menu

		$id_product_menu = get_field('main-products-campain-menu', 'option');
		wp_update_nav_menu_item($id_product_menu, 0, array(
			'menu-item-title' => $name,
			'menu-item-object-id' => $sedoo_campaign_product_id,    
			'menu-item-object' => 'sedoo_camp_products',
			'menu-item-type' => 'post_type',
			'menu-item-status' => 'publish')
		);
		
	}
	
	//// USE THE GOOD VIEWER FOR THE PRODUCT
	$id_default_viewer = get_field('id_viewer_defaut', 'option');

	$post_for_content_creation = array(
		'ID'           => $sedoo_campaign_product_id,
		'post_content'	=> '<!-- wp:acf/sedoo-campaign-default-viewer {"id":"block_600ab64e89314","name":"acf/sedoo-campaign-default-viewer", "data":{"field_5f846db6e9d25":["'.$id_default_viewer.'"],"field_5f858dbfb1014":["'.$slug.'"]},"align":"","mode":"preview"} /-->'
	);

	// Update the post into the database
	wp_update_post( $post_for_content_creation );

	update_field( 'field_600976ee6a445', $name, $sedoo_campaign_product_id);
	update_field( 'field_600977076a446', $slug, $sedoo_campaign_product_id);
	wp_die();
}
// END CREATE OR UPDATE A PRODUCT
///////


///////
// UPDATE GLOBAL META
add_action('wp_ajax_sedoo_campaign_update_option_meta', 'sedoo_campaign_update_option_meta');
add_action('wp_ajax_nopriv_sedoo_campaign_update_option_meta', 'sedoo_campaign_update_option_meta');
function sedoo_campaign_update_option_meta() {
	$metakey = $_POST['metakey'];
	$metavalue = $_POST['metavalue'];
	$fieldType = $_POST['fieldtype']; 
	update_field( $metakey, $metavalue, 'option' );

	wp_die();
}
// END UPDATE GLOBAL META
///////



///////
// REGISTER VIEWER BLOC
function sedoo_campaing_register_viewer_bloc_callback( $block ) {
	
	$templateURL = plugin_dir_path(__FILE__) . "blocs/viewerdefault.php";
    // include a template part from within the "template-parts/block" folder
    
	if( file_exists( $templateURL)) {
		include $templateURL;
    }
}

function sedoo_campaing_register_viewer_bloc() {

    // register a testimonial block.
    acf_register_block_type(array(
        'name'              => 'sedoo_campaign_default_viewer',
        'title'             => __('Default Viewer'),
        'description'       => __('Ajoute un viewer par défaut'),
        'render_callback'   => 'sedoo_campaing_register_viewer_bloc_callback',
        'category'          => 'widgets',
        'icon'              => 'admin-site-alt2',
        'keywords'          => array( 'viewers', 'sedoo' ),
    ));
}

// Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'sedoo_campaing_register_viewer_bloc');
}

	////////
	// BLOC GUTENBERG DU VIEWERS
	///////
	acf_add_local_field_group(array(
		'key' => 'group_5f846daf38429',
		'title' => 'Champs pour bloc misva',
		'fields' => array(
			array(
				'key' => 'field_5f846db6e9d25',
				'label' => 'Type de viewer à charger',
				'name' => 'type_de_viewer_a_charger',
				'type' => 'relationship',
				'instructions' => 'Incluez un viewer spécifique aux données qui seront traitées et affichées.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'sedoo_camp_viewers',
				),
				'taxonomy' => '',
				'filters' => '',
				'elements' => '',
				'min' => '0',
				'max' => '1',
				'return_format' => 'id',
			),
			array(
				'key' => 'field_5f858dbfb1014',
				'label' => 'Produits à afficher',
				'name' => 'produits_a_afficher',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
				),
				'default_value' => array(
				),
				'allow_null' => 0,
				'multiple' => 1,
				'ui' => 1,
				'ajax' => 0,
				'return_format' => 'array',
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/sedoo-campaign-default-viewer',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));

// END REGISTER VIEWER BLOC
///////


///////
// SINGLE PRODUCT PAGE
// template
add_filter( 'single_template', 'sedoo_campaign_single_product_load_template' );
function sedoo_campaign_single_product_load_template( $single_template ) {
    global $post;
 
    if ( 'sedoo_camp_products' === $post->post_type ) {
        $single_template = dirname( __FILE__ ) . '/single-sedoo_camp_products.php';
    }
 
    return $single_template;
}

// css
add_action( 'wp_enqueue_scripts', 'sedoo_campaign_single_product_load_css' );
function sedoo_campaign_single_product_load_css() {
    if ( 'sedoo_camp_products' === get_post_type() ) {
		wp_register_style( 'sedoo_campaign_product_single_css', plugins_url( 'css/front.css', __FILE__ ) );
	    wp_enqueue_style( 'sedoo_campaign_product_single_css' );
    }
}
// END SINGLE PRODUCT PAGE
//////