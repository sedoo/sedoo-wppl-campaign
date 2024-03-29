<?php
/**
 * Get all registered menus
 * @return array List of menus with slug and description
 */
function sedoo_wppl_campaign_wp_api_v2_menus_get_all_menus() {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );

	foreach ( $menus as $key => $menu ) {
		// check if there is acf installed
		if ( class_exists( 'acf' ) ) {
			$fields = get_fields( $menu );
			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field_key => $item ) {
					// add all acf custom fields
					$menus[ $key ]->$field_key = $item;
				}
			}
		}
	}
	
	return apply_filters('sedoo_wppl_campaign_wp_api_v2_menus__menus', $menus);
}

/**
 * Get all locations
 * @return array List of locations
 **/

function wp_api_v2_menu_get_all_locations() {
	$nav_menu_locations = get_nav_menu_locations();
	$locations          = new stdClass;
	foreach ( $nav_menu_locations as $location_slug => $menu_id ) {
		if ( get_term( $location_slug ) !== null ) {
			$locations->{$location_slug} = get_term( $location_slug );
		} else {
			$locations->{$location_slug} = new stdClass;
		}
		$locations->{$location_slug}->slug = $location_slug;
		$locations->{$location_slug}->menu = get_term( $menu_id );
	}

	return apply_filters('sedoo_wppl_campaign_wp_api_v2_menus__locations', $locations);
}

/**
 * Get menu's data from his id
 *
 * @param array $data WP REST API data variable
 *
 * @return object Menu's data with his items
 */
function wp_api_v2_locations_get_menu_data( $data ) {
	// Create default empty object
	$menu = new stdClass;

	// this could be replaced with `if (has_nav_menu($data['id']))`
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $data['id'] ] ) ) {
		// Replace default empty object with the location object
		$menu        = get_term( $locations[ $data['id'] ] );
		$menu->items = sedoo_wppl_campaign_wp_api_v2_menus_get_menu_items( $locations[ $data['id'] ] );
	} else {
		return new WP_Error( 'not_found', 'No location has been found with this id or slug: `' . $data['id'] . '`. Please ensure you passed an existing location ID or location slug.', array( 'status' => 404 ) );
	}

	// check if there is acf installed
	if ( class_exists( 'acf' ) ) {
		$fields = get_fields( $menu );
		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field_key => $item ) {
				// add all acf custom fields
				$menu->$field_key = $item;
			}
		}
	}

	return apply_filters('sedoo_wppl_campaign_wp_api_v2_menus__menu', $menu);
}

/**
 * Check if a menu item is child of one of the menu's element passed as reference
 *
 * @param $parents Menu's items
 * @param $child Menu's item to check
 *
 * @return bool True if the parent is found, false otherwise
 */
function sedoo_wppl_campaign_wp_api_v2_menus_dna_test( &$parents, $child ) {
	foreach ( $parents as $key => $item ) {
		if ( $child->menu_item_parent == $item->ID ) {
			if ( ! $item->child_items ) {
				$item->child_items = [];
			}
			array_push( $item->child_items, $child );
			return true;
		}

		if($item->child_items) {
			if(sedoo_wppl_campaign_wp_api_v2_menus_dna_test($item->child_items, $child)) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Search object in an array by ID
 */
function wp_api_v2_find_object_by_id( $array, $id ) {
	foreach ( $array as $element ) {
		if ( $id == $element->ID ) {
				return $element;
		}
	}

	return false;
}

/**
 * Retrieve items for a specific menu
 *
 * @param $id Menu id
 *
 * @return array List of menu items
 */
function sedoo_wppl_campaign_wp_api_v2_menus_get_menu_items( $id ) {
	$menu_items = wp_get_nav_menu_items( $id );
	$all_menu_items = $menu_items;

	// check if there is acf installed
	if ( class_exists( 'acf' ) ) {
		foreach ( $menu_items as $menu_key => $menu_item ) {
			$fields = get_fields( $menu_item->ID );
			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field_key => $item ) {
					// add all acf custom fields
					
					$menu_items[ $menu_key ]->$field_key = $item;
				}
			}
		}
	}

	// wordpress does not group child menu items with parent menu items
	$child_items = [];
	// pull all child menu items into separate object
	foreach ( $menu_items as $key => $item ) {

		if($item->type == 'post_type') {
			// add slug to menu items
			$slug = basename( get_permalink($item->object_id) );
			$item->slug = $slug;
		} else if($item->type == 'taxonomy') {
			$cat = get_term($item->object_id);
			$item->slug = $cat->slug;
		} else if($item->type == 'post_type_archive') {
			$post_type_data = get_post_type_object($item->object);

			if ($post_type_data->has_archive) {
				$item->slug = $post_type_data->rewrite['slug'];
			}
		}
		if (isset($item->thumbnail_id) && $item->thumbnail_id) {
			$item->thumbnail_src = wp_get_attachment_image_url(intval($item->thumbnail_id), 'post-thumbnail');
		}
		if (isset($item->thumbnail_hover_id) && $item->thumbnail_hover_id) {
			$item->thumbnail_hover_src = wp_get_attachment_image_url(intval($item->thumbnail_hover_id), 'post-thumbnail');
		}

		if ( $item->menu_item_parent ) {
			array_push( $child_items, $item );
			unset( $menu_items[ $key ] );
		}
	}

	// push child items into their parent item in the original object
	do {
		foreach($child_items as $key => $child_item) {
			$parent = wp_api_v2_find_object_by_id( $all_menu_items, $child_item->menu_item_parent );

			if ( empty( $parent ) ) {
				unset($child_items[$key]);
			}

			else if (sedoo_wppl_campaign_wp_api_v2_menus_dna_test($menu_items, $child_item)) {
				unset($child_items[$key]);
			}
		}
	} while(count($child_items));
	return apply_filters('sedoo_wppl_campaign_wp_api_v2_menus__menu_items', array_values($menu_items));
}

/**
 * Get menu's data from his id.
 *    It ensures compatibility for previous versions when this endpoint
 *    was allowing locations id in place of menus id)
 *
 * @param array $data WP REST API data variable
 *
 * @return object Menu's data with his items
 */
function sedoo_wppl_campaign_wp_api_v2_menus_get_menu_data( $data ) {
	// This ensure retro compatibility with versions `<= 0.5` when this endpoint
	//   was allowing locations id in place of menus id
	if ( has_nav_menu( $data['id'] ) ) {
		$menu = wp_api_v2_locations_get_menu_data( $data );
	} else if ( is_nav_menu( $data['id'] ) ) {
		if ( is_int( $data['id'] ) ) {
			$id = $data['id'];
		} else {
			$id = wp_get_nav_menu_object( $data['id'] );
		}
		$menu        = get_term( $id );
		$menu->items = sedoo_wppl_campaign_wp_api_v2_menus_get_menu_items( $id );
	} else {
		return new WP_Error( 'not_found', 'No menu has been found with this id or slug: `' . $data['id'] . '`. Please ensure you passed an existing menu ID, menu slug, location ID or location slug.', array( 'status' => 404 ) );
	}

	// check if there is acf installed
	if ( class_exists( 'acf' ) ) {
		$fields = get_fields( $menu );
		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field_key => $item ) {
				// add all acf custom fields
				$menu->$field_key = $item;
			}
		}
	}

	return apply_filters('sedoo_wppl_campaign_wp_api_v2_menus__menu', $menu);
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'menus/v1', '/menus', array(
		'methods'  => 'GET',
		'callback' => 'sedoo_wppl_campaign_wp_api_v2_menus_get_all_menus',
		'permission_callback' => '__return_true'
	) );

	register_rest_route( 'menus/v1', '/menus/(?P<id>[a-zA-Z0-9_-]+)', array(
		'methods'  => 'GET',
		'callback' => 'sedoo_wppl_campaign_wp_api_v2_menus_get_menu_data',
		'permission_callback' => '__return_true'
	) );

	register_rest_route( 'menus/v1', '/locations/(?P<id>[a-zA-Z0-9_-]+)', array(
		'methods'  => 'GET',
		'callback' => 'wp_api_v2_locations_get_menu_data',
		'permission_callback' => '__return_true'
	) );

	register_rest_route( 'menus/v1', '/locations', array(
		'methods'  => 'GET',
		'callback' => 'wp_api_v2_menu_get_all_locations',
		'permission_callback' => '__return_true'
	) );
} );
