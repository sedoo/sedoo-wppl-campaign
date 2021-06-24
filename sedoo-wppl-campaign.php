<?php
/**
 * Plugin Name: Sedoo - Campagnes
 * Description: Plugin nécessaire sur un wordpress type campagne
 * Version: 0.3.0
 * Author: Nicolas Gruwe 
 * GitHub Plugin URI: sedoo/sedoo-wppl-campaign
 * GitHub Branch:     master
 */
 
include 'sedoo-wppl-posttypes.php'; // post types viewers & product
include 'sedoo-wppl-campaign-func.php'; // post types viewers & product
include 'sedoo-wppl-admin-param-page.php'; // admin parameters page
include 'inc/sedoo-wppl-campaign-menu-json.php';

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
	
	$post_for_content_creation = array(
		'ID'           => $sedoo_campaign_product_id,
		'post_content'	=> ''
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

	////////
	// CHAMPS ACF DU BLOC DEFAULT VIEWERS
	///////
	acf_add_local_field_group(array(
		'key' => 'group_5f846daf38429',
		'title' => 'Champs pour bloc misva',
		'fields' => array(
			array(
				'key' => 'field_5f858dbfb1014',
				'label' => 'Produits à afficher',
				'name' => 'produits_a_afficher',
				'type' => 'relationship',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'sedoo_camp_products',
				),
				'taxonomy' => '',
				'filters' => '',
				'elements' => '',
				'min' => '0',
				'max' => '1',
				'return_format' => 'id',
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

	// SINGLE PRODUCT FIELDS
	acf_add_local_field_group(array(
		'key' => 'group_60c21d19d8896',
		'title' => 'Informations',
		'fields' => array(
			array(
				'key' => 'field_60c21d3e5ddfc',
				'label' => 'Label \'informations\'',
				'name' => 'sedoo_campaign_product_label_informations',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Details',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_60c21d1edc109',
				'label' => 'Informations',
				'name' => 'sedoo_campaign_product_information',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sedoo_camp_products',
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
		
	// END SINGLE PRODUCT FIELDS

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