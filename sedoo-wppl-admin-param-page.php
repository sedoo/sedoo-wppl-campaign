<?php 

include 'sedoo-campaign-mainadmin.php';

//////
// THE PARAMETER ADMINISTRATION PAGE
if( function_exists('acf_add_options_page') ) {
	// the 
	acf_add_options_page(array(
		'page_title' 	=> 'Paramètres de campagnes',
		'menu_title'	=> 'Paramètres de campagne',
		'menu_slug' 	=> 'sedoo-campaign-admin-page',
		'capability'	=> 'edit_posts',
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
				'key' => 'field_600ac80c3e15c',
				'label' => 'Menu des produits',
				'name' => 'main-products-campain-menu',
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
				'key' => 'field_6006f6b745489',
				'label' => 'ID viewer par défaut',
				'name' => 'id_viewer_defaut',
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
				'readonly'=> 0,
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
// END THE ADMINISTRATION PAGE

add_action('admin_menu', 'sedoo_campaign_menu');

function sedoo_campaign_menu() {
    add_menu_page( 'sedoo-campaign-main-admin-page', 'Ma campagne', 'Ma campagne',
     'sedoo-campaign-admin-main-page', 'sedoo_main_admin_page_func');
}

?>