<?php

///////
// REGISTER DEFAULT VIEWER BLOC
function sedoo_campaign_register_viewer_bloc_callback($block)
{
    $product_id = get_field('produits_a_afficher');
    $product_service_urls = get_option("swc_product_service_urls");
    $type_produit = get_field('type', $product_id[0]);
    $package_url = $product_service_urls->$type_produit->packageUrl;
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
        'title'             => __('Viewer de produit'),
        'description'       => __('Ajoute un viewer de produit'),
        'render_callback'   => 'sedoo_campaign_register_viewer_bloc_callback',
        'category'          => 'widgets',
        'icon'              => 'admin-site-alt2',
        'keywords'          => array('viewers', 'produit', 'sedoo'),
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
    if ('sedoo_camp_products' === get_post_type()) {
        wp_register_style('font-awesome', "https://use.fontawesome.com/releases/v5.0.13/css/all.css");
        wp_enqueue_style('font-awesome');
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


/**/
// Adding Dashicons in WordPress Front-end
/**/
add_action('wp_enqueue_scripts', 'load_dashicons_front_end');
function load_dashicons_front_end()
{
    wp_enqueue_style('dashicons');
}

///////
// CREATE THE PRODUCT MENU
function sedoo_campaign_init_product_menu()
{
    if (get_option('swc_products_menu_id')) {  // si le menu des produits est déjà selectionné je fais rien
    } else { // sinon je le crée et je l'associe
        $productMenuId = wp_create_nav_menu('sedoo-campaign-product-main-menu');
        update_option('swc_products_menu_id', $productMenuId);
    }
}
add_action('init', 'sedoo_campaign_init_product_menu', 0);
// END CREATE THE PRODUCT MENU
///////

///////
// CREATE THE MAIN MENU
function sedoo_campaign_init_main_menu()
{
    if (get_option('swc_main_menu_id')) { // si le menu principal est déjà selectionné je fais rien
    } else {
        if (has_nav_menu('primary-menu')) { // si un menu est déjà présent alors on utilise celui la
            $mainMenuId = get_term(get_nav_menu_locations()['primary-menu'], 'nav_menu')->term_id;
            update_option('swc_main_menu_id', $mainMenuId);
        } else { // sinon je le crée et on utilise celui la
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

//////
// CREATE DATA POLICY FRONT PAGE
function sedoo_campaign_create_data_policy_page()
{
    if (!get_option('swc_data_policy_page_id')) {
        $datapolicycontent = "Ici mon contenu";
        $page_id = sedoo_campaign_create_post('Data Policy', $datapolicycontent, 'page', 'swc_data_policy_page_id');
        if (get_option('swc_main_menu_id')) {
            $id_main_menu = get_option('swc_main_menu_id');
            $id_data_acces_item = get_option('swc_data_access_menu_item_id');
            $item = wp_update_nav_menu_item(
                $id_main_menu,
                0,
                array(
                    'menu-item-title' => 'Data Policy',
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
add_action('init', 'sedoo_campaign_create_data_policy_page', 0);
// END CREATE DATA POLICY FRONT PAGE
//////

///////
// CREATE THE CATALOGUE WEB COMPONENT
// NB : this function updates acf fields from the sedoo-wppl-components plugin
function sedoo_campaign_init_create_catalogue()
{
    if (!get_option('swc_catalogue_component_id')) {
        $catalogueWebComponentArgs = array(
            'post_title'    => wp_strip_all_tags('Catalogue'),
            'post_name'        => 'catalogue',
            'post_status'   => 'publish',
            'post_type'        => 'vuejs',
            'post_author'   => 1
        );
        $catalogue_component_Id = wp_insert_post($catalogueWebComponentArgs);
        update_option('swc_catalogue_component_id', $catalogue_component_Id); // update campaign option
        $scripts_values = array(
            array("script"   => '<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>'),
            array("script"   => '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.15.0/prism.min.js">/script>'),
            array("script"   => '<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>'),
            array("script"   => '<script>  document.ssoAerisInitOptions = {url : "https://sso.aeris-data.fr/auth",     realm : "aeris",     clientId : "$$CAMPAIGNNAME$$-vjs",     resource: "catalogue-aeris-services",  authorizedDomains: ["https://services.aeris-data.fr/"]    } </script>'),
            array("script"   => '<script src="https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sedoo/snapshot/aeris-catalogue-component/0.1.0-snapshot"></script>')
        );
        update_field('elements_inclus', $scripts_values, $catalogue_component_Id); // update viewer scripts

        $block_content = '<aeris-catalogue language="$$LANGUAGE$$" project="$$CAMPAIGNNAME$$"  blank-request="true"></aeris-catalogue>';

        update_field('contenu_du_block', $block_content, $catalogue_component_Id); // update viewer div

        // CREATE CATALOGUE PAGE
        $contenu_page_catalogue = '<!-- wp:acf/sedoo-blocks-vuejs {"id":"block_6023afff16ad3","name":"acf/sedoo-blocks-vuejs","data":{"field_5e663f64b0b3a":["' . $catalogue_component_Id . '"]},"align":"","mode":"preview"} /-->';
        $page_catalogue_id = sedoo_campaign_create_post('Page Catalogue', $contenu_page_catalogue, 'page', 'swc_catalogue_page_id');
        if (get_option('swc_main_menu_id')) {
            $id_product_menu = get_option('swc_main_menu_id');
            $id_data_acces_item = get_option('swc_data_access_menu_item_id');
            wp_update_nav_menu_item(
                $id_product_menu,
                0,
                array( // ADD PAGE TO THE MAIN MENU
                    'menu-item-title' => 'Catalogue',
                    'menu-item-object-id' => $page_catalogue_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $id_data_acces_item
                )
            );
        }
    }
}
add_action('init', 'sedoo_campaign_init_create_catalogue', 0);
// END CREATE THE CATALOGUE WEB COMPONENT
///////
