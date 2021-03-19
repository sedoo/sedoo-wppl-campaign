<?php
if(is_admin() == true) {
    ///////
    // Pas besoin de montrer le composant si je suis en Admin, juste dire qu'il est la
    //////
    ?>
    <style>
    div.sedoo_related_block_admin_block {
        text-align: center;
        background: #c8c8c8;
        color:#535050;
        padding: 15px;
        padding-top: 1px;
    }
    </style>
    <?php 
        echo '<div class="sedoo_related_block_admin_block"><h2> Bloc Viewer </h2> <span> Visible seulement en front-office </span></div>';
} else {
    ?>
    <section class="sedoo-campaign-view-product">
    <?php 
        $product_id = get_field('produits_a_afficher');
        $product = get_field('id', $product_id[0]); // get product id and name
        $breadcrumb = get_field('name', $product_id[0]); // get product id and name
        $type_produit = get_field('type', $product_id[0]);


        $campaign = get_field('nom_de_la_campagne', 'option');   

        // get service url and package url from option page
        while( have_rows('field_6054902922fe1', 'option') ) : the_row();
        if(get_sub_field('type_de_produit') == $type_produit) {
            $service_url = get_sub_field('url_du_service');
            $package_url = get_sub_field('url_du_package');
        }
        endwhile;

        // selection du type de viewer
        $type_viewer = '';
        switch ($type_produit) {
            case 'calendarbasedproduct':
                break;
            case 'filetree':
                $type_viewer= 'viewer="tree"';
                break;
            case 'wmsproduct':
                $type_viewer= 'viewer="wmts"';
                break;
        }
    ?>            
    <script src="<?php echo $package_url; ?>"></script>
    <campaign-product <?php echo $attribut_viewer; ?> service="<?php echo $service_url; ?>" campaign="<?php echo $campaign; ?>" product="<?php echo $product; ?>" breadcrumb="<?php echo $breadcrumb; ?>" vce-ready="">
</section> 
<?php } ?>
