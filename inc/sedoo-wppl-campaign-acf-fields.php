<?php
if (function_exists('acf_add_local_field_group')) :
    // SINGLE PRODUCT FIELDS
    acf_add_local_field_group(array(
        'key' => 'group_60c21d19d8896',
        'title' => 'Options',
        'fields' => array(
            array(
                'key' => 'field_60c21d3e5ddfd',
                'label' => 'Label for description',
                'name' => 'sedoo_campaign_product_description_label',
                'type' => 'text',
                'instructions' => 'Description is the main editorial content',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 'Product description',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_60c21d3e5ddfc',
                'label' => 'Label \'informations\'',
                'name' => 'sedoo_campaign_product_label_informations',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 'Informations',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_60c21d1edc109',
                'label' => 'Informations content',
                'name' => 'sedoo_campaign_product_information',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
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

    // END SINGLE PRODUCT FIELDS

    // register field for CPT products

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
                'key' => 'field_600979ee6a655',
                'label' => 'Type',
                'name' => 'type',
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
        'menu_order' => 2,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));


    ////////
    // CHAMPS ACF DU BLOC DEFAULT VIEWERS
    ///////
    acf_add_local_field_group(array(
        'key' => 'group_5f846daf38429',
        'title' => 'Champs pour bloc misva',
        'fields' => array(
            array(
                'key' => 'field_5f858dbfb1014',
                'label' => 'Produits à afficher',
                'name' => 'produits_a_afficher',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'sedoo_camp_products',
                ),
                'taxonomy' => '',
                'filters' => '',
                'elements' => '',
                'min' => '0',
                'max' => '1',
                'return_format' => 'id',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/sedoo-campaign-default-viewer',
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
