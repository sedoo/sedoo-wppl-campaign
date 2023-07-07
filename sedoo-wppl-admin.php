<?php

/**
 * Campaign admin page
 */
function sedoo_campaign_admin_page_view()
{
    $is_admin_attr = '';
    if (is_super_admin()) {
        $is_admin_attr = "is-admin='true'";
    }

    $first_setup_done_attr = '';
    if (get_option('swc_first_setup_done', false)) {
        $first_setup_done_attr = "first-setup-done='true'";
    }

    $campaign_name_attr = '';
    if ($campaignName = get_option('swc_campaign_name')) {
        // get campaign data from backend
        // $response = sedoo_campaign_call_api("GET", CAMPAIGNS_SERVICE_URL . "/campaign/v1_0/findbyname/" . $campaignName);
        $campaign_name_attr = "campaign-name='" . $campaignName . "'";
    }

    $campaign_settings = get_option('swc_settings', false);
    $campaign_settings_attr = '';
    if ($campaign_settings !== false && $campaign_settings != null) {
        $campaign_settings_attr = "campaign-settings='" . json_encode($campaign_settings) . "'";
    }

    $services_by_product_type = get_option('swc_product_service_urls', false);
    $product_services_attr = '';
    if ($services_by_product_type !== false && $services_by_product_type != null) {
        $product_services_attr = "services-by-product-type='" . json_encode($services_by_product_type) . "'";
    }

?>
    <div class="sedoo_admin_bloc">
        <sedoocampaigns-admin <?= $is_admin_attr ?> <?= $first_setup_done_attr ?> campaigns-service="<?= CAMPAIGNS_SERVICE_URL ?>" <?= $campaign_settings_attr ?> <?= $campaign_name_attr ?> <?= $product_services_attr ?>></sedoocampaigns-admin>
    </div>
    <?php }

///////
// INCLUDE BACK CSS FOR CAMPAIGN ADMIN PAGE
function sedoo_campaign_include_admin_scripts()
{
    if (get_admin_page_title() == "sedoo-campaign-main-admin-page") {
        wp_enqueue_style('sedoo_campaign_back_css', plugin_dir_url(__FILE__) . '/css/back.css');
        wp_register_script('sedoocampaign-ajax', plugin_dir_url(__FILE__) . 'js/widget_main_page.js', null, null, true);
        wp_enqueue_script('sedoocampaign-ajax');

        wp_register_script('sedoocampaigns-vjs', JS_PACKAGE_URL, null, "0.1.0", false);
        wp_enqueue_script('sedoocampaigns-vjs');
    }
}
add_action('admin_enqueue_scripts', 'sedoo_campaign_include_admin_scripts');
// END INCLUDE BACK CSS FOR CAMPAIGN ADMIN PAGE
///////

//////
// THE MAIN ADMINISTRATION PAGE
add_action('init', 'sedoo_campaign_menu');
function sedoo_campaign_menu()
{
    add_menu_page(
        'sedoo-campaign-main-admin-page',
        'My Campaign',
        'administrator',
        'sedoo-campaign-admin-main-page',
        'sedoo_campaign_admin_page_view',
        '',
        98
    );
}
// END THE MAIN ADMINISTRATION PAGE
//////

///////
// DETECT IF USER TRIED TO DELETE A POST THAT IS USED IN CAMPAIGN (JUST SHOW MESSAGE HERE)
add_action('admin_notices', 'sedoo_campaign_wp_notice_deletepostusedincampaign');
function sedoo_campaign_wp_notice_deletepostusedincampaign()
{
    $message = get_transient(get_current_user_id() . '_sedoo_campaign_transient_cantremovepost_error');

    if ($message) {
        delete_transient(get_current_user_id() . '_sedoo_campaign_transient_cantremovepost_error');

        printf(
            '<div class="%1$s"><p>%2$s</p></div>',
            'notice notice-error is-dismissible _sedoo_campaign_transient_cantremovepost_error',
            $message
        );
    }
}
// DETECT IF USER TRIED TO DELETE A POST THAT IS USED IN CAMPAIGN (JUST SHOW MESSAGE HERE)
///////

///////
// CHECK FOR REQUIRED PLUGINS
///////
add_action('admin_notices', 'sedoo_campaign_notice');
function sedoo_campaign_notice()
{
    if (!class_exists('ACF')) :
    ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Please activate Advanced Custom Fields, it is required for <strong>' . get_plugin_data(__FILE__)['Name'] .  '</strong> plugin to work properly.', 'my_plugin_textdomain'); ?></p>
        </div>
    <?php endif;
    if (!sedoo_campaign_is_plugin_active('sedoo-wppl-components/sedoo-wppl-components.php')) : ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Please activate SEDOO - VUE JS Components, it is required for <strong>' . get_plugin_data(__FILE__)['Name'] .  '</strong> plugin to work properly.', 'my_plugin_textdomain'); ?></p>
        </div>
    <?php endif;
}
// END CHECK FOR REQUIRED PLUGINS
///////

//////
// REMOVE DELETE POSSIBILITIE FOR THE USED ID
// THE DIFFERENTS POST TYPES
function sedoo_campaign_remove_delete_possibilitie_post_types($post_ID)
{
    $restrictedIdArray = [];
    // get catalogue and data policy page
    array_push($restrictedIdArray, get_option('swc_catalogue_page_id'));
    array_push($restrictedIdArray, get_option('swc_data_policy_page_id'));
    array_push($restrictedIdArray, get_option('swc_user_manager_page_id'));
    // get catalogue web component
    array_push($restrictedIdArray, get_option('swc_catalogue_component_id'));
    array_push($restrictedIdArray, get_option('swc_user_manager_component_id'));
    // get default viewer -> does not exist
    // array_push($restrictedIdArray, get_option('swc_default_viewer_id'));

    if (in_array($post_ID, $restrictedIdArray)) {
        // save a transient with the message that user can't delete this
        set_transient(
            get_current_user_id() . '_sedoo_campaign_transient_cantremovepost_error',
            __("This content is needed for the campaign manager. It cannot be deleted.", 'sedoo_campaign_remove_notice')
        );
        // redirect the user and exit to prevent deletion
        wp_redirect(admin_url());

        exit;
    }
}
add_action('wp_trash_post', 'sedoo_campaign_remove_delete_possibilitie_post_types', 1);
add_action('rest_delete_sedoo_camp_viewers', 'sedoo_campaign_remove_delete_possibilitie_post_types', 1);
add_action('rest_delete_vuejs', 'sedoo_campaign_remove_delete_possibilitie_post_types', 1);
add_action('rest_delete_pages', 'sedoo_campaign_remove_delete_possibilitie_post_types', 1);


// THE MENUS (product menu and main menu)

function sedoo_campaign_remove_delete_possibilitie_menus($menu_ID)
{
    $restrictedMenuArray = [48];
    // get product menu id        
    // array_push($restrictedMenuArray, get_field('main-products-campaign-menu', 'option'));
    // get main menu id
    // array_push($restrictedMenuArray, get_field('main-campaign-menu', 'option'));

}
add_action('wp_delete_nav_menu', 'sedoo_campaign_remove_delete_possibilitie_menus', 10, 1);
// END REMOVE DELETE POSSIBILITIE FOR THE USED ID
//////

/**
 * Adds css color variable in the admin head to be used by the sedoocampaigns-admin vue.js component
 */
add_action('admin_head', function () {
    global $_wp_admin_css_colors;
    ?>
    <style>
        :root {
            --theme-color: <?= $_wp_admin_css_colors[get_user_option('admin_color')]->colors[2] ?>;
        }
    </style>
<?php
});
