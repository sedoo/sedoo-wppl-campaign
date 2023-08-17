<?php

/**
 * Activate Sedoo Components plugin
 */
add_action('plugins_loaded', 'sedoo_campaign_activate_required_plugins');
function sedoo_campaign_activate_required_plugins()
{
    $plugin_path = 'sedoo-wppl-components/sedoo-wppl-components.php';
    $active_plugins = get_option('active_plugins');
    if (isset($active_plugins[$plugin_path])) {
        // if the plugin is already active, do nothing
        return;
    }

    // Include the plugin.php file so you have access to the activate_plugin() function
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
    // Activate your plugin
    activate_plugin($plugin_path);
}

///////
// REGISTER DEFAULT VIEWER BLOC
function sedoo_campaign_register_viewer_bloc_callback($block)
{
    $product_id = get_field('products_to_display');
    $product_service_urls = get_option("swc_product_service_urls");
    $product_type = get_field('type', $product_id[0]);
    $package_url = $product_service_urls->$product_type->packageUrl;
    // enqueue specific script for the block
    $script_handle = 'js-package-' . $product_id[0];
    wp_enqueue_script($script_handle, $package_url);

    $templateURL = plugin_dir_path(__FILE__) . "blocs/viewerdefault.php";
    // include a template part from within the "template-parts/block" folder

    if (file_exists($templateURL)) {
        include $templateURL;
    }
}

function sedoo_campaign_register_viewer_bloc()
{

    // register a testimonial block.
    acf_register_block_type(array(
        'name'              => 'sedoo_campaign_default_viewer',
        'title'             => __('Product viewer'),
        'description'       => __('Adds a product viewer'),
        'render_callback'   => 'sedoo_campaign_register_viewer_bloc_callback',
        'category'          => 'widgets',
        'icon'              => 'admin-site-alt2',
        'keywords'          => array('viewers', 'product', 'sedoo'),
    ));
}

// Check if function exists and hook into setup.
if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'sedoo_campaign_register_viewer_bloc');
}
// END REGISTER DEFAULT VIEWER BLOC
///////


///////
// SINGLE PRODUCT PAGE
// template
add_filter('single_template', 'sedoo_campaign_single_product_load_template');
function sedoo_campaign_single_product_load_template($single_template)
{
    global $post;

    if ('sedoo_camp_products' === $post->post_type) {
        $single_template = dirname(__FILE__) . '/templates/single-sedoo_camp_products.php';
    }

    return $single_template;
}
// END SINGLE PRODUCT PAGE
//////


///////
// PRODUCTS ARCHIVE PAGE
// template
add_filter('archive_template', 'sedoo_campaign_archive_product_load_template');
function sedoo_campaign_archive_product_load_template($archive_template)
{
    global $post;

    if (is_post_type_archive('sedoo_camp_products')) {
        $archive_template = dirname(__FILE__) . '/templates/archive-sedoo_camp_products.php';
    }
    return $archive_template;
}
// PRODUCTS ARCHIVE PAGE
//////

/////
// INCLUDE FRONT CSS
add_action('wp_enqueue_scripts', 'sedoo_campaign_single_product_load_css');
function sedoo_campaign_single_product_load_css()
{
    if ('sedoo_camp_products' === get_post_type() || is_post_type_archive('sedoo_camp_products')) {
        wp_register_style('roboto-font', "https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900");
        wp_enqueue_style('roboto-font');
        wp_register_style('mdi', "https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css");
        wp_enqueue_style('mdi');
        wp_register_style('sedoo_campaign_product_single_css', plugins_url('css/front.css', __FILE__));
        wp_enqueue_style('sedoo_campaign_product_single_css');
        wp_register_script('sedoocampaigns-vjs', JS_PACKAGE_URL, null, "0.1.0", false);
        wp_enqueue_script('sedoocampaigns-vjs');
        wp_register_script('single-product-js', plugin_dir_url(__FILE__) . 'js/single_front.js', null, null, true);
        wp_enqueue_script('single-product-js');
    }
}
// END INCLUDE FRONT CSS
//////

///////
// CREATE THE PRODUCT MENU
function sedoo_campaign_init_product_menu()
{
    if ($productMenuId = get_option('swc_products_menu_id')) {  // if products menu exists, do nothing
    } else { // else, create the products menu
        $productMenuId = wp_create_nav_menu('sedoo-campaign-product-main-menu');
        update_option('swc_products_menu_id', $productMenuId);
    }
}
add_action('admin_init', 'sedoo_campaign_init_product_menu', 0);
// END CREATE THE PRODUCT MENU
///////

///////
// CREATE THE MAIN MENU
function sedoo_campaign_init_main_menu()
{
    if (get_option('swc_main_menu_id')) { // if main menu exists, do nothing
    } else {
        if (has_nav_menu('primary-menu')) { // if a primary menu already exists, use that one
            $mainMenuId = get_term(get_nav_menu_locations()['primary-menu'], 'nav_menu')->term_id;
            update_option('swc_main_menu_id', $mainMenuId);
        } else { // else, create the main menu and use that one
            $mainMenuId = wp_create_nav_menu('sedoo-campaign-main-menu');
            update_option('swc_main_menu_id', $mainMenuId);
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary-menu'] = $mainMenuId;
            set_theme_mod('nav_menu_locations', $locations);
        }
        $menu_data_access_item = wp_update_nav_menu_item($mainMenuId, 0, array(
            'menu-item-title' => 'Data Access',
            'menu-item-url' => '#',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom', // optional
        ));
        update_option('swc_data_access_menu_item_id', $menu_data_access_item);
    }
}
add_action('init', 'sedoo_campaign_init_main_menu', 0);
// END CREATE THE MAIN MENU
///////

add_action('init', 'sedoo_campaign_page_handler');
function sedoo_campaign_page_handler()
{
    if (false === $settings = get_option('swc_settings')) return;

    if ($settings->dataPolicy === "true") {
        sedoo_campaign_create_page('swc_data_policy_page_id', 'Data Policy', 'My data policy content here.');
    } else {
        sedoo_campaign_delete_page('swc_data_policy_page_id');
    }

    if ($settings->catalogue === "true") {
        $catalogue_component_id = sedoo_campaign_create_component('swc_catalogue_component_id', 'Catalogue', 'https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sandbox/release/aeris-catalogue-component/3.0.0', '<sedoo-catalogue language="$$LANGUAGE$$" project="$$CAMPAIGNNAME$$"  blank-request="true"></sedoo-catalogue>');
        $catalogue_page_content = '<!-- wp:acf/sedoo-blocks-vuejs {"id":"block_6023afff16ad3","name":"acf/sedoo-blocks-vuejs","data":{"field_5e663f64b0b3a":["' . $catalogue_component_id . '"]},"align":"","mode":"preview"} /-->';
        sedoo_campaign_create_page('swc_catalogue_page_id', 'Catalogue', $catalogue_page_content);
    } else {
        sedoo_campaign_delete_page('swc_catalogue_page_id');
        sedoo_campaign_delete_page('swc_catalogue_component_id');
    }

    if ($settings->userManagement === "true") {
        $user_manager_component_id = sedoo_campaign_create_component('swc_user_manager_component_id', 'User Manager', 'https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sandbox/release/sedoo-access-vjs/0.1.0', '<span><div id="app"></div></span>');
        $user_manager_page_content = '<!-- wp:acf/sedoo-blocks-vuejs {"id":"block_6023afff16ad3","name":"acf/sedoo-blocks-vuejs","data":{"field_5e663f64b0b3a":["' . $user_manager_component_id . '"]},"align":"","mode":"preview"} /-->';
        sedoo_campaign_create_page('swc_user_manager_page_id', 'User Manager', $user_manager_page_content);
    } else {
        sedoo_campaign_delete_page('swc_user_manager_page_id');
        sedoo_campaign_delete_page('swc_user_manager_component_id');
    }
}
