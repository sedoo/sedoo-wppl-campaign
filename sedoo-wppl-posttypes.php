<?php
///////
// CREATE THE PRODUCT POST TYPE
// Register Custom Post Type
function sedoo_campaign_unregister_product_post_type()
{
	unregister_post_type('sedoo_camp_products');
}

function sedoo_campaign_register_product_post_type()
{
	$labels = array(
		'name'                  => _x('Produits', 'Post Type General Name', 'text_domain'),
		'singular_name'         => _x('Produit', 'Post Type Singular Name', 'text_domain'),
		'menu_name'             => __('Produits', 'text_domain'),
		'name_admin_bar'        => __('Produits', 'text_domain'),
		'archives'              => __('Products', 'text_domain'),
		'attributes'            => __('Products Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('All Items', 'text_domain'),
		'add_new_item'          => __('Add New Item', 'text_domain'),
		'add_new'               => __('Add New', 'text_domain'),
		'new_item'              => __('New Item', 'text_domain'),
		'edit_item'             => __('Edit Item', 'text_domain'),
		'update_item'           => __('Update Item', 'text_domain'),
		'view_item'             => __('View Item', 'text_domain'),
		'view_items'            => __('View Items', 'text_domain'),
		'search_items'          => __('Search Item', 'text_domain'),
		'not_found'             => __('Not found', 'text_domain'),
		'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
		'featured_image'        => __('Featured Image', 'text_domain'),
		'set_featured_image'    => __('Set featured image', 'text_domain'),
		'remove_featured_image' => __('Remove featured image', 'text_domain'),
		'use_featured_image'    => __('Use as featured image', 'text_domain'),
		'insert_into_item'      => __('Insert into item', 'text_domain'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
		'items_list'            => __('Items list', 'text_domain'),
		'items_list_navigation' => __('Items list navigation', 'text_domain'),
		'filter_items_list'     => __('Filter items list', 'text_domain'),
	);
	$args = array(
		'label'                 => __('Produit', 'text_domain'),
		'description'           => __('Produits de campagnes', 'text_domain'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'revisions'),
		'taxonomies'            => array('category', 'post_tag'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_rest' => true, // Important !
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'rewrite'				=> array('slug' => 'products'),
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'products',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type('sedoo_camp_products', $args);
}

add_action('init', function () {
	$settings = get_option('swc_settings');
	$products_archive_menu_item_id = get_option('swc_products_archive_menu_item', 0);
	if ($settings->products === "true") {
		sedoo_campaign_register_product_post_type();
		sedoo_campaign_create_products();
		$id_data_acces_item = get_option('swc_data_access_menu_item_id');
		$products_archive_menu_item_id = wp_update_nav_menu_item(get_option('swc_main_menu_id'), $products_archive_menu_item_id, array(
			'menu-item-title' => 'Products',
			'menu-item-status' => 'publish',
			'menu-item-object' => 'sedoo_camp_products',
			'menu-item-type' => 'post_type_archive',
			'menu-item-parent-id' => $id_data_acces_item
		));
		update_option('swc_products_archive_menu_item', $products_archive_menu_item_id);
	} else {
		sedoo_campaign_unregister_product_post_type();
		sedoo_campaign_remove_products();
		wp_delete_post($products_archive_menu_item_id);
		delete_option('swc_products_archive_menu_item');
	}
});
// END CREATE THE PRODUCT POST TYPE
///////
