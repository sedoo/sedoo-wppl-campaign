<?php

/**
 * All things AJAX
 */
add_action('wp_ajax_sedoo_campaign_create_or_update_product', 'sedoo_campaign_create_or_update_product');
add_action('wp_ajax_nopriv_sedoo_campaign_create_or_update_product', 'sedoo_campaign_create_or_update_product');
function sedoo_campaign_create_or_update_product()
{
    if (!isset($_POST['product']['_class']) || !isset($_POST['product']['id']) || $_POST['product']['id'] == 0) {
        return;
    }
    $name = $_POST['product']['name'];
    $slug = $_POST['product']['id'];
    $menu_id = get_option('swc_products_menu_id');
    $menu_items = wp_get_nav_menu_items($menu_id);
    $product_id = sedoo_campaign_the_slug_exists($slug, 'sedoo_camp_products');
    if ($product_id === 0) {
        $product_menu_id = 0;
        $menu_item_parent = "0";
        $menu_item_position = 0;
    } else {
        $product_menu_item = sedoo_campaign_find_item_in_menu($product_id, $menu_items);
        $product_menu_id = $product_menu_item->ID;
        $menu_item_parent = $product_menu_item->menu_item_parent;
        $menu_item_position = $product_menu_item->menu_order;
    }

    $product = array(
        'ID'            => $product_id,
        'post_title'    => $name,
        'post_name'     => $slug,
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'sedoo_camp_products'
    );

    // add or update product
    $post_id = wp_insert_post($product);
    update_field('field_600976ee6a445', $name, $post_id); // name field
    update_field('field_600977076a446', $slug, $post_id); // id field
    update_field('field_600979ee6a655', $_POST['product']['_class'], $post_id); // type field


    // add product to product menu or update product and keep its position
    $item_args = array(
        'menu-item-title' => $name,
        'menu-item-object-id' => $post_id,
        'menu-item-object' => 'sedoo_camp_products',
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish',
        'menu-item-position' => $menu_item_position,
        'menu-item-parent-id' => $menu_item_parent
    );
    wp_update_nav_menu_item($menu_id, $product_menu_id, $item_args);

    wp_die();
}
// END CREATE OR UPDATE A PRODUCT
///////

//////
// CHECK IF PRODUCT MENU IS EXISTING OR NOT BEFORE IMPORT PRODUCTS
add_action('wp_ajax_sedoo_campaign_check_product_menu', 'sedoo_campaign_check_product_menu');
add_action('wp_ajax_nopriv_sedoo_campaign_check_product_menu', 'sedoo_campaign_check_product_menu');
function sedoo_campaign_check_product_menu()
{
    $response = [
        'status' => 'success',
        'message' => 'The menu already exists.',
    ];
    if (wp_get_nav_menu_object('sedoo-campaign-product-main-menu') == false) { // si il n'y a plus de menu produits, j'en recrée un
        $productMenuId = wp_create_nav_menu('sedoo-campaign-product-main-menu');
        update_option('swc_products_menu_id', $productMenuId);
        if (is_int($productMenuId)) {
            $response = [
                'status' => 'success',
                'message' => 'The menu was successfully created.',
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'An error occured when attempting to create the menu.',
            ];
        }
    }
    echo json_encode($response);
    wp_die();
}
// END CHECK IF PRODUCT MENU IS EXISTING OR NOT BEFORE IMPORT PRODUCTS
//////


add_action('wp_ajax_sedoo_campaign_check_and_delete_missing_products_in_the_flux', 'sedoo_campaign_check_and_delete_missing_products_in_the_flux');
add_action('wp_ajax_nopriv_sedoo_campaign_check_and_delete_missing_products_in_the_flux', 'sedoo_campaign_check_and_delete_missing_products_in_the_flux');
/////////
//  END CHECK FOR MISSING PRODUCTS IN THE JS FLUX
function sedoo_campaign_check_and_delete_missing_products_in_the_flux()
{
    $productsIdArray = $_POST['productsIdArray']; // get backend products id array
    $get_wp_products_args = array(
        'numberposts' => -1,
        'post_type'   => 'sedoo_camp_products'
    );
    $AllWPProductsList = get_posts($get_wp_products_args);
    foreach ($AllWPProductsList as $WPproduct) { // foreach product in WP, if is not in the js anymore, just delete it
        $WPproduct_campaign_id = get_field('id', $WPproduct->ID);
        if (in_array($WPproduct_campaign_id, $productsIdArray)) {
        } else {
            wp_delete_post($WPproduct->ID);
        }
    }

    wp_die();
}
//  END CHECK FOR MISSING PRODUCTS IN THE JS FLUX
/////////


///////
// UPDATE GLOBAL META FOR JS AJAX CALL
add_action('wp_ajax_sedoo_campaign_update_option_meta', 'sedoo_campaign_update_option_meta');
add_action('wp_ajax_nopriv_sedoo_campaign_update_option_meta', 'sedoo_campaign_update_option_meta');
function sedoo_campaign_update_option_meta()
{
    $metakey = $_POST['metakey'];
    $metavalue = $_POST['metavalue'];

    if (gettype($metavalue) === "array") {
        // update service urls
        update_option($metakey, sedoo_campaign_array_to_object($metavalue));
    } else {
        // update backend_id and campaign name
        update_option($metakey, $metavalue);
    }

    wp_die();
}
// END UPDATE GLOBAL META FOR JS AJAX CALL
///////