<?php

/**
 * Register options in the wp_options table
 */

/**
 * Name of the campaign
 */
add_option('swc_campaign_name', '', '', 'no');

/**
 * Backend ID of the campaign
 */
add_option('swc_campaign_id', '', '', 'no');

/**
 * ID of the products menu
 */
add_option('swc_products_menu_id', '', '', 'no');

/**
 * ID of the campaign main menu
 */
add_option('swc_main_menu_id', '', '', 'no');

/**
 * ID of the data policy page
 */
add_option('swc_data_policy_page_id', '', '', 'no');

/**
 * ID of the catalogue page
 */
add_option('swc_catalogue_page_id', '', '', 'no');

/**
 * ID of the catalogue component
 * @see sedoo-wppl-components 
 * @link https://github.com/sedoo/sedoo-wppl-components
 */
add_option('swc_catalogue_component_id', '', '', 'no');

/**
 * ID of the Data Access menu item
 */
add_option('swc_data_access_menu_item_id', '', '', 'no');

/**
 * URLs if services and packages by product type
 * @var object $value 
 */
add_option('swc_product_service_urls', '', '', 'no');
