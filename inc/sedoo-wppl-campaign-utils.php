<?php

// Constants
if ($_SERVER['HTTP_HOST'] === "localhost") {
    define("JS_PACKAGE_URL", "http://10.186.10.96:8081/js/app.js");
} else {
    define("JS_PACKAGE_URL", "https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0");
}
define("CAMPAIGNS_SERVICE_URL", "https://api.sedoo.fr/sedoo-campaigns-rest");

/**
 * Define product menu api url
 */
$product_nav_menu_id = get_option('swc_products_menu_id');
if ($product_nav_menu_id) {
    $menu_api_url = home_url() . "/wp-json/menus/v1/menus/" . $product_nav_menu_id;
} else {
    $menu_api_url = "";
}
define("MENU_API_URL", $menu_api_url);

/**
 * Define plugin options
 */
define("SWC_PLUGIN_OPTIONS",  array(
    'swc_campaign_id',
    'swc_campaign_name',
    'swc_catalogue_component_id',
    'swc_catalogue_page_id',
    'swc_data_access_menu_item_id',
    'swc_data_policy_page_id',
    'swc_first_setup_done',
    'swc_main_menu_id',
    'swc_product_service_urls',
    'swc_products_archive_menu_item',
    'swc_products_menu_id',
    'swc_settings',
    'swc_user_manager_component_id',
    'swc_user_manager_page_id'
));

// Utils
function sedoo_campaign_call_api($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}


function sedoo_campaign_array_to_object(array $arr)
{
    if (is_array($arr)) {
        // Return object 
        return json_decode(json_encode($arr));
    }
    return false;
}

function sedoo_campaign_is_plugin_active($path)
{
    $active_plugins = get_option('active_plugins');
    $components_plugin_active = in_array($path, $active_plugins);
    return $components_plugin_active;
}

///////
// CREATE OR UPDATE A PRODUCT
///////
// Func to check if product already exist
function sedoo_campaign_the_slug_exists($slug, $post_type)
{
    $args = array(
        'name'   => $slug,
        'post_type'   => $post_type,
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $sedoo_campaign_product_exist = get_posts($args);
    if ($sedoo_campaign_product_exist) {
        return $sedoo_campaign_product_exist[0]->ID;
    } else {
        return 0;
    }
}

function sedoo_campaign_find_item_in_menu($item, $menu_items)
{
    foreach ($menu_items as $menu_item) {
        if ($menu_item->object_id == $item) {
            return $menu_item;
        }
    }
    return 0;
}

///////
// CREATE A SIMPLE POST
function sedoo_campaign_create_post($title, $content, $post_type, $optionfield, $post_status = 'publish')
{
    $added_post = array(
        'post_title'    => $title,
        'post_content'  => $content,
        'post_status'   => $post_status,
        'post_author'   => 1,
        'post_type' => $post_type
    );
    $post_id = wp_insert_post($added_post);
    update_option($optionfield, $post_id);
    return $post_id;
}
// END CREATE A SIMPLE POST
///////

function sedoo_campaign_create_component($option_name, $component_title, $source_url, $block_content)
{
    if (!get_option($option_name)) {
        $component_args = array(
            'post_title'    => wp_strip_all_tags($component_title),
            'post_name'     => str_replace(' ', '-', strtolower($component_title)),
            'post_status'   => 'publish',
            'post_type'     => 'vuejs',
            'post_author'   => 1
        );
        $component_id = wp_insert_post($component_args);
        update_option($option_name, $component_id); // update campaign option
        $scripts_values = array(
            array("script"   => '<script src="' . $source_url . '"></script>')
        );
        update_field('elements_inclus', $scripts_values, $component_id); // update viewer scripts
        update_field('contenu_du_block', $block_content, $component_id); // update viewer div
        return $component_id;
    }
}

function sedoo_campaign_create_page($option_name, $page_title, $page_content = "")
{
    if (!get_option($option_name)) {
        $page_id = sedoo_campaign_create_post($page_title, $page_content, 'page', $option_name);
        if ($id_main_menu = get_option('swc_main_menu_id', false)) {
            $id_data_acces_item = get_option('swc_data_access_menu_item_id');
            wp_update_nav_menu_item(
                $id_main_menu,
                0,
                array(
                    'menu-item-title' => $page_title,
                    'menu-item-object-id' => $page_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $id_data_acces_item
                )
            );
        }
    }
}

function sedoo_campaign_delete_page($option_name)
{
    if ($id = get_option($option_name)) {
        wp_delete_post($id, true);
        delete_option($option_name);
    }
}