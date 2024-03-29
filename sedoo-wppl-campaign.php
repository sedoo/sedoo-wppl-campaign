<?php
/**
 * Plugin Name: Sedoo - Campaign product viewer
 * Description: Déclarer une campagne, ses produits et les viewers associés
 * Version: 1.1.1
 * Author: Pierre Vert & Nicolas Gruwe 
 * GitHub Plugin URI: sedoo/sedoo-wppl-campaign
 * GitHub Branch:     master
 */
 
include 'sedoo-wppl-posttypes.php'; // post types viewers & product
include 'sedoo-wppl-campaign-func.php'; // post types viewers & product
include 'sedoo-wppl-admin-param-page.php'; // admin parameters page
include 'inc/sedoo-wppl-campaign-menu-json.php';
include 'inc/sedoo-wppl-campaign-acf-fields.php';

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

		// check if product menu is empty, so if is empty, insert product into products menu
		$id_product_menu = get_field('main-products-campain-menu', 'option');
		$product_menu = wp_get_nav_menu_object($id_product_menu);
		if($product_menu->count == 0) {
			wp_update_nav_menu_item($id_product_menu, 0, array(
				'menu-item-title' => $name,
				'menu-item-object-id' => $sedoo_campaign_product_id,    
				'menu-item-object' => 'sedoo_camp_products',
				'menu-item-type' => 'post_type',
				'menu-item-status' => 'publish')
			);
		}
	}
	else {
		// INSERT THE NEW PRODUCT
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
	
	$sedoo_campaign_product_content = get_post_field('post_content', $sedoo_campaign_product_id);

	$post_for_content_creation = array(
		'ID'           => $sedoo_campaign_product_id,
		'post_content'	=> $sedoo_campaign_product_content,
		
	);

	// Update the post into the database
	wp_update_post( $post_for_content_creation );

	update_field( 'field_600976ee6a445', $name, $sedoo_campaign_product_id); // name field
	update_field( 'field_600977076a446', $slug, $sedoo_campaign_product_id); // id field
	update_field( 'field_600979ee6a655', $_POST['product']['_class'], $sedoo_campaign_product_id); // type field

	
	wp_die();
}
// END CREATE OR UPDATE A PRODUCT
///////


add_action('wp_ajax_sedoo_campaign_check_and_delete_missing_products_in_the_flux', 'sedoo_campaign_check_and_delete_missing_products_in_the_flux');
add_action('wp_ajax_nopriv_sedoo_campaign_check_and_delete_missing_products_in_the_flux', 'sedoo_campaign_check_and_delete_missing_products_in_the_flux');
/////////
//  END CHECK FOR MISSING PRODUCTS IN THE JS FLUX
function sedoo_campaign_check_and_delete_missing_products_in_the_flux() {
	$productsIdArray = $_POST['productsIdArray']; // get backend products id array
	$get_wp_products_args = array(
		'numberposts' => -1,
		'post_type'   => 'sedoo_camp_products'
	);
	$AllWPProductsList = get_posts( $get_wp_products_args );
	foreach($AllWPProductsList as $WPproduct) { // foreach product in WP, if is not in the js anymore, just delete it
		$WPproduct_campaign_id = get_field('id', $WPproduct->ID);
		if(in_array($WPproduct_campaign_id, $productsIdArray)) {
		} else {
			wp_delete_post($WPproduct->ID);
		}
	}
}
//  END CHECK FOR MISSING PRODUCTS IN THE JS FLUX
/////////


///////
// UPDATE GLOBAL META FOR JS AJAX CALL
add_action('wp_ajax_sedoo_campaign_update_option_meta', 'sedoo_campaign_update_option_meta');
add_action('wp_ajax_nopriv_sedoo_campaign_update_option_meta', 'sedoo_campaign_update_option_meta');
function sedoo_campaign_update_option_meta() {
	$metakey = $_POST['metakey'];
	$metavalue = $_POST['metavalue'];
	$fieldType = $_POST['fieldtype']; 
	update_field( $metakey, $metavalue, 'option' );

	wp_die();
}
// END UPDATE GLOBAL META FOR JS AJAX CALL
///////

///////
// REGISTER DEFAULT VIEWER BLOC
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
			'title'             => __('Viewer de produit'),
			'description'       => __('Ajoute un viewer de produit'),
			'render_callback'   => 'sedoo_campaing_register_viewer_bloc_callback',
			'category'          => 'widgets',
			'icon'              => 'admin-site-alt2',
			'keywords'          => array( 'viewers', 'produit', 'sedoo' ),
		));
	}

	// Check if function exists and hook into setup.
	if( function_exists('acf_register_block_type') ) {
		add_action('acf/init', 'sedoo_campaing_register_viewer_bloc');
	}


// END REGISTER DEFAULT VIEWER BLOC
///////


///////
// SINGLE PRODUCT PAGE
// template
add_filter( 'single_template', 'sedoo_campaign_single_product_load_template' );
function sedoo_campaign_single_product_load_template( $single_template ) {
    global $post;
 
    if ( 'sedoo_camp_products' === $post->post_type ) {
        $single_template = dirname( __FILE__ ) . '/templates/single-sedoo_camp_products.php';
    }
 
    return $single_template;
}

// END SINGLE PRODUCT PAGE
//////


///////
// PRODUCTS ARCHIVE PAGE
// template
add_filter( 'archive_template', 'sedoo_campaign_archive_product_load_template' );
 
function sedoo_campaign_archive_product_load_template( $archive_template ) {
     global $post;
 
     if ( is_post_type_archive ( 'sedoo_camp_products' ) ) {
          $archive_template = dirname( __FILE__ ) . '/templates/archive-sedoo_camp_products.php';
     }
     return $archive_template;
}
// PRODUCTS ARCHIVE PAGE
//////

/////
// INCLUDE FRONT CSS
add_action( 'wp_enqueue_scripts', 'sedoo_campaign_single_product_load_css' );
function sedoo_campaign_single_product_load_css() {
    if ( 'sedoo_camp_products' === get_post_type() ) {
		wp_register_style( 'sedoo_campaign_product_single_css', plugins_url( 'css/front.css', __FILE__ ) );
	    wp_enqueue_style( 'sedoo_campaign_product_single_css' );
    }
}
// END INCLUDE FRONT CSS
//////


/**/
// Adding Dashicons in WordPress Front-end
/**/
add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
  wp_enqueue_style( 'dashicons' );
}