<?php 
///////
// CREATE THE PRODUCT MENU
function sedoo_campaign_init_product_menu() {
    if(get_field('main-products-campain-menu', 'option')) {  // si le menu des produits est déjà selectionné je fais rien
    }
    else { // sinon je le crée et je l'associe
        $productMenuId = wp_create_nav_menu('sedoo-campain-product-main-menu');
        update_field('main-products-campain-menu', $productMenuId, 'option');
    }
}
add_action( 'init', 'sedoo_campaign_init_product_menu', 0 );
// END CREATE THE PRODUCT MENU
///////


///////
// CREATE THE MAIN MENU
function sedoo_campaign_init_main_menu() {
    if(get_field('main-campain-menu', 'option')) { // si le menu principal est déjà selectionné je fais rien
    } 
    else {
        if(has_nav_menu( 'primary-menu' )) { // si un menu est déjà présent alors on utilise celui la
            $mainMenuId = get_term(get_nav_menu_locations()['primary-menu'], 'nav_menu')->term_id;
            update_field('main-campain-menu', $mainMenuId, 'option');
        }
        else { // sinon je le crée et on utilise celui la
            $mainMenuId = wp_create_nav_menu('sedoo-campain-main-menu');
            update_field('main-campain-menu', $mainMenuId, 'option');
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary-menu'] = $mainMenuId;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }
}
add_action( 'init', 'sedoo_campaign_init_main_menu', 0 );
// END CREATE THE MAIN MENU
///////


///////
// CREATE A SIMPLE POST
function sedoo_campaign_create_post($title, $content, $post_type, $optionfield) {
    $added_post = array(
        'post_title'    => $title,
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type' => $post_type
    );
    $post_id = wp_insert_post( $added_post );
    update_field($optionfield, $post_id, 'option');
    return $post_id;
}
// END CREATE A SIMPLE POST
///////

//////
// CREATE DATA POLICY FRONT PAGE
function sedoo_campaign_create_data_policy_page() {
    if(!get_field('id_page_data_policy', 'option')) { 
        $datapolicycontent = "Ici mon contenu";
        $page_id = sedoo_campaign_create_post('Data Policy', $datapolicycontent, 'page', 'id_page_data_policy');
        if(get_field('main-campain-menu', 'option')) { 
            $id_product_menu = get_field('main-campain-menu', 'option');
            var_dump($id_product_menu);
            wp_update_nav_menu_item($id_product_menu, 0, array(
                'menu-item-title' => 'Data Policy',
                'menu-item-object-id' => $page_id,
                'menu-item-object' => 'page',
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish')
            );
        }
    }
}
add_action( 'init', 'sedoo_campaign_create_data_policy_page', 0 );
// END CREATE DATA POLICY FRONT PAGE
//////

///////
// CREATE THE CATALOGUE WEB COMPONENT
function sedoo_campaign_init_create_catalogue() {
    if(!get_field('id_composant_catalogue', 'option')) {
        $catalogueWebComponentArgs = array(
            'post_title'    => wp_strip_all_tags( 'Catalogue' ),
            'post_name'		=> 'catalogue',
            'post_status'   => 'publish',
            'post_type'		=> 'vuejs',
            'post_author'   => 1
        );
        $catalogue_component_Id = wp_insert_post( $catalogueWebComponentArgs );
        update_field( 'id_composant_catalogue', $catalogue_component_Id, 'option' ); // update campaign option
        $scripts_values = array(
			array("script"   => '<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>'),
			array("script"   => '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.15.0/prism.min.js">/script>'),
			array("script"   => '<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>'),
			array("script"   => '<script>  document.ssoAerisInitOptions = {     url : "https://sso.aeris-data.fr/auth",     realm : "aeris",     clientId : "$$CAMPAIGNNAME$$-vjs",     resource: "catalogue-aeris-services",  authorizedDomains: ["https://services.aeris-data.fr/"]    } </script>'),
			array("script"   => '<script src="https://services.aeris-data.fr/cdn/jsrepo/v1_0/download/sedoo/snapshot/aeris-catalogue-component/0.1.0-snapshot"></script>')
		);
		update_field( 'elements_inclus', $scripts_values, $catalogue_component_Id ); // update viewer scripts

        $block_content = '<aeris-catalogue language="en" project="$$CAMPAIGNNAME$$"  blank-request="true"></aeris-catalogue>';

        update_field( 'contenu_du_block', $block_content, $catalogue_component_Id ); // update viewer div

        // CREATE CATALOGUE PAGE
        $contenu_page_catalogue = '<!-- wp:acf/sedoo-blocks-vuejs {"id":"block_6023afff16ad3","name":"acf/sedoo-blocks-vuejs","data":{"field_5e663f64b0b3a":["'.$catalogue_component_Id.'"]},"align":"","mode":"preview"} /-->';
        $page_catalogue_id = sedoo_campaign_create_post('Page Catalogue', $contenu_page_catalogue, 'page', 'id_page_catalogue');
        if(get_field('main-campain-menu', 'option')) { 
            $id_product_menu = get_field('main-campain-menu', 'option');
            wp_update_nav_menu_item($id_product_menu, 0, array( // ADD PAGE TO THE MAIN MENU
                'menu-item-title' => 'Catalogue',
                'menu-item-object-id' => $page_catalogue_id,    
                'menu-item-object' => 'page',
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish')
            );
        }
    }
}
add_action( 'init', 'sedoo_campaign_init_create_catalogue', 0 );
// END CREATE THE CATALOGUE WEB COMPONENT
///////


///////
// CREATE THE DEFAULT VIEWER
function sedoo_campaign_init_create_viewers() {
	if(!get_field('id_viewer_defaut', 'option')) {
		$defaultviewers_args = array(
			'post_title'    => wp_strip_all_tags( 'Viewer par défaut' ),
			'post_name'		=> 'default-viewer',
			'post_status'   => 'publish',
			'post_type'		=> 'sedoo_camp_viewers',
			'post_author'   => 1
		);
	
		$defautviewer_Id = wp_insert_post( $defaultviewers_args );
		update_field( 'id_viewer_defaut', $defautviewer_Id, 'option' ); // update campaign option

		$scripts_values = array(
			array(
				"script_misva"   => '<script src="https://services.aeris-data.fr/cdn/jsrepo/v1_0/download/sedoo/snapshot/misva-components/0.0.1-snapshot"></script>'
            )
		);
		update_field( 'elements_inclus_misva', $scripts_values, $defautviewer_Id ); // update viewer scripts



		$params_values = array(
			array(
				"nom_de_lattribut"   => 'service',
				"valeur_de_lattribut" => 'https://services.aeris-data.fr/campaigns/data/v1_0'
			)
		);
		update_field( 'repeteur_attributs_misva', $params_values, $defautviewer_Id ); // update viewer scripts
		update_field( 'nom_de_la_balise', 'campaign-component', $defautviewer_Id ); // update viewer div

		update_field( 'field_600976ee6a445', $name, $sedoo_campaign_product_id);
	}
}
add_action( 'init', 'sedoo_campaign_init_create_viewers', 0 );
// END CREATE THE DEFAULT VIEWER
///////

//////
// REMOVE DELETE POSSIBILITIE FOR THE USED ID

    // THE DIFFERENTS POST TYPES
    function sedoo_campaign_remove_delete_possibilitie_post_types($post_ID){
        $restrictedIdArray = [];
        // get catalogue and data policy page
        array_push($restrictedIdArray, get_field('id_page_catalogue', 'option'));
        array_push($restrictedIdArray, get_field('id_page_data_policy', 'option'));
        // get catalogue web component
        array_push($restrictedIdArray, get_field('id_composant_catalogue', 'option'));
        // get default viewer 
        array_push($restrictedIdArray, get_field('id_viewer_defaut', 'option'));

        if(in_array($post_ID, $restrictedIdArray))
        {
            echo "Vous ne pouvez pas supprimer ce contenu, il est utilisé dans l'outil de campagne.";
            exit;
        }
    }
    add_action('wp_trash_post', 'sedoo_campaign_remove_delete_possibilitie_post_types', 10, 1);


    // THE MENUS (product menu and main menu)

    function sedoo_campaign_remove_delete_possibilitie_menus($menu_ID){
        $restrictedMenuArray = [48];
        // get product menu id        
       // array_push($restrictedMenuArray, get_field('main-products-campain-menu', 'option'));
        // get main menu id
       // array_push($restrictedMenuArray, get_field('main-campain-menu', 'option'));
      
    }
    add_action('wp_delete_nav_menu', 'sedoo_campaign_remove_delete_possibilitie_menus', 10, 1);
// END REMOVE DELETE POSSIBILITIE FOR THE USED ID
//////

?>