<?php

/**
 * Cleanup plugin data on uninstall
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}


/**
 * Remove plugin options
 */
foreach (SWC_PLUGIN_OPTIONS as $option) {
    if (get_option($option)) {
        delete_option($option);
    }
}


/**
 * Delete products
 */
$sedoo_cpt_args = array('post_type' => 'sedoo_camp_products', 'posts_per_page' => -1);
$sedoo_cpt_posts = get_posts($sedoo_cpt_args);
foreach ($sedoo_cpt_posts as $post) {
    // false : to put the products in the trash ; true : to delete theme completely
    wp_delete_post($post->ID, false);
}
