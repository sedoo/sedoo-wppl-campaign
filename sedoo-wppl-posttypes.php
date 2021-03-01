<?php 
///////
// CREATE THE PRODUCT POST TYPE AND HIS FIELDS ACF
// Register Custom Post Type
	function sedoo_campaign_register_product_post_type() {

		$labels = array(
			'name'                  => _x( 'Produits', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Produit', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Produits', 'text_domain' ),
			'name_admin_bar'        => __( 'Produits', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
			'attributes'            => __( 'Item Attributes', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Items', 'text_domain' ),
			'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Item', 'text_domain' ),
			'edit_item'             => __( 'Edit Item', 'text_domain' ),
			'update_item'           => __( 'Update Item', 'text_domain' ),
			'view_item'             => __( 'View Item', 'text_domain' ),
			'view_items'            => __( 'View Items', 'text_domain' ),
			'search_items'          => __( 'Search Item', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __( 'Produit', 'text_domain' ),
			'description'           => __( 'Produits de campagnes', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_rest' => true, // Important !
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'rewrite'				=> array('slug' => 'products'),
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'sedoo_camp_products', $args );

	}
	add_action( 'init', 'sedoo_campaign_register_product_post_type', 0 );

	// register field for products
	if( function_exists('acf_add_local_field_group') ):
		acf_add_local_field_group(array(
			'key' => 'group_600976e621bfa',
			'title' => 'Groupe de champs d\'un produit de campagne',
			'fields' => array(
				array(
					'key' => 'field_600976ee6a445',
					'label' => 'name',
					'name' => 'name',
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
					'readonly' => 1,
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_600977076a446',
					'label' => 'id',
					'name' => 'id',
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
					'readonly' => 1,
					'maxlength' => '',
				)
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'sedoo_camp_products',
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
	endif;
// END CREATE THE PRODUCT POST TYPE AND FIELDS
///////

///////
// CREATE THE VIEWERS POST TYPE AND HIS FIELDS ACF
// Register Custom Post Type
	function sedoo_campaign_register_viewers_post_type() {

		$labels = array(
			'name'                  => _x( 'Viewers', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Viewer', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Viewers', 'text_domain' ),
			'name_admin_bar'        => __( 'Viewers', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
			'attributes'            => __( 'Item Attributes', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Items', 'text_domain' ),
			'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Item', 'text_domain' ),
			'edit_item'             => __( 'Edit Item', 'text_domain' ),
			'update_item'           => __( 'Update Item', 'text_domain' ),
			'view_item'             => __( 'View Item', 'text_domain' ),
			'view_items'            => __( 'View Items', 'text_domain' ),
			'search_items'          => __( 'Search Item', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __( 'Produit', 'text_domain' ),
			'description'           => __( 'Produits de campagnes', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'sedoo_camp_viewers', $args );

	}
	add_action( 'init', 'sedoo_campaign_register_viewers_post_type', 0 );

	// register fields for viewer post type
	///////
	if( function_exists('acf_add_local_field_group') ):
		acf_add_local_field_group(array(
			'key' => 'group_5e663e0f3f1c0',
			'title' => 'Post VUE JS',
			'fields' => array(
				array(
					'key' => 'field_5e663e6c5e3e8',
					'label' => 'Elements inclus',
					'name' => 'elements_inclus_misva',
					'type' => 'repeater',
					'instructions' => 'Insérer les lignes d\'inclusion des fichiers css, js, typographiques',
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
					'button_label' => '+ Ligne de script',
					'sub_fields' => array(
						array(
							'key' => 'field_5e763e455e7e9',
							'label' => 'Ligne de script',
							'name' => 'script_misva',
							'type' => 'text',
							'instructions' => 'Les lignes complètes, incluant les balises < link > ou < script >',
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
				array(
					'key' => 'field_5f846de9e9d26',
					'label' => 'Attributs et valeurs du viewer',
					'name' => 'repeteur_attributs_misva',
					'type' => 'repeater',
					'instructions' => 'Insérez ici, sur chaque ligne les couples attributs et valeurs',
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
					'button_label' => '+ Attribut / Valeur',
					'sub_fields' => array(
						array(
							'key' => 'field_5f846e18e9d27',
							'label' => 'Nom de l\'attribut',
							'name' => 'nom_de_lattribut',
							'type' => 'text',
							'instructions' => 'Ex : Service, ..',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '40',
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
							'key' => 'field_5f846e2fe9d28',
							'label' => 'Valeur de l\'attribut',
							'name' => 'valeur_de_lattribut',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '40',
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
				array(
					'key' => 'field_5f856353381bc',
					'label' => 'Nom de la balise',
					'name' => 'nom_de_la_balise',
					'type' => 'text',
					'instructions' => 'Insérez ici le nom de la balise du viewer',
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
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'sedoo_camp_viewers',
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
	endif;
// END CREATE THE VIEWERS POST TYPE AND FIELDS
///////


?>