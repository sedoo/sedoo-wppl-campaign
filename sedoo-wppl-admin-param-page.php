<?php 
include 'sedoo-campaign-mainadmin.php';
//////
// THE CAMPAIGN SETTINGS ADMINISTRATION PAGE
if( function_exists('acf_add_options_page') ) {
	// the page
	acf_add_options_page(array(
		'page_title' 	=> 'Paramètres de campagnes',
		'menu_title'	=> 'Paramètres de campagne',
		'menu_slug' 	=> 'sedoo-campaign-admin-page',
		'parent_slug'	=> 'sedoo-campaign-admin-main-page',
		'capability'	=> 'manage_options',
		'redirect'		=> false
    ));
    
	// the fields for the admin param page
	acf_add_local_field_group(array(
		'key' => 'group_6006f6aa811d5',
		'title' => 'Paramètres de campagne',
		'fields' => array(
			array(
				'key' => 'field_6006f6b727127',
				'label' => 'Nom de la campagne',
				'name' => 'nom_de_la_campagne',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),	
			array(
				'key' => 'field_6006f6b727171',
				'label' => 'ID item menu data access',
				'name' => 'data_access_menu_item',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'readonly'=> 1,
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_600ac80c3e15c',
				'label' => 'ID Menu des produits',
				'name' => 'main-products-campain-menu',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'readonly'=> 0,
				'maxlength' => '',
			),
			array(
				'key' => 'field_600ac80c3e16c',
				'label' => 'ID Menu principal de la campagne',
				'name' => 'main-campain-menu',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'readonly'=> 1,
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_6006f6b745457',
				'label' => 'Id du back end de la campagne',
				'name' => 'id_back_end_campagne',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'readonly'=> 1,
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_6006f6b745590',
				'label' => 'ID composant web catalogue',
				'name' => 'id_composant_catalogue',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'readonly'=> 1,
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_6006f6b748756',
				'label' => 'ID page data policy',
				'name' => 'id_page_data_policy',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'readonly'=> 1,
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_6006f6b759246',
				'label' => 'ID page catalogue',
				'name' => 'id_page_catalogue',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hidden',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'readonly'=> 1,
				'append' => '',
				'maxlength' => '',
			)
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'sedoo-campaign-admin-page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
}
// END THE CAMPAIGN SETTINGS ADMINISTRATION PAGE
//////


//////
// THE SERVICES SETTINGS ADMINISTRATION PAGE
if( function_exists('acf_add_options_page') ) {
	// the page
	acf_add_options_page(array(
		'page_title' 	=> 'Paramètres de services',
		'menu_title'	=> 'Paramètres de services',
		'menu_slug' 	=> 'sedoo-campaign-services-admin-page',
		'parent_slug'	=> 'sedoo-campaign-admin-main-page',
		'capability'	=> 'manage_options',
		'redirect'		=> false
    ));
    
	acf_add_local_field_group(array(
		'key' => 'group_605488ae93475',
		'title' => 'Url de services',
		'fields' => array(
			array(
				'key' => 'field_6054902922fe1',
				'label' => '',
				'name' => '',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_6054903222fe2',
						'label' => 'Type de produit',
						'name' => 'type_de_produit',
						'type' => 'text',
						'instructions' => 'Ex : calendarbasedproduct, filetree',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_6054904c22fe3',
						'label' => 'URL du service',
						'name' => 'url_du_service',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_6054905222fe4',
						'label' => 'URL du package',
						'name' => 'url_du_package',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'sedoo-campaign-services-admin-page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));

    
}
// END THE SERVICES SETTINGS ADMINISTRATION PAGE
//////

///////
// INCLUDE BACK CSS FOR CAMPAIGN ADMIN PAGE
function sedoo_campaign_include_back_css($hook_suffix) {
	if($hook_suffix == 'toplevel_page_sedoo-campaign-admin-main-page') {
		wp_enqueue_style('sedoo_campain_back_css', plugin_dir_url( __FILE__ ) . '/css/back.css');
	}
}
add_action('admin_enqueue_scripts', 'sedoo_campaign_include_back_css');
// END INCLUDE BACK CSS FOR CAMPAIGN ADMIN PAGE
///////

//////
// THE MAIN ADMINISTRATION PAGE
add_action('admin_menu', 'sedoo_campaign_menu');

function sedoo_campaign_menu() {
    add_menu_page( 'sedoo-campaign-main-admin-page', 'Ma campagne', 'administrator',
     'sedoo-campaign-admin-main-page', 'sedoo_main_admin_page_func'); // in sedoo-campaign-mainadmin.php
}
// END THE MAIN ADMINISTRATION PAGE
//////

?>