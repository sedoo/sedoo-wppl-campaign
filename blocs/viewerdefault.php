<?php
if (is_admin() == true) {
    ///////
    // Pas besoin de montrer le composant si je suis en Admin, juste dire qu'il est la
    //////
?>
    <style>
        div.sedoo_related_block_admin_block {
            text-align: center;
            background: #c8c8c8;
            color: #535050;
            padding: 15px;
            padding-top: 1px;
        }
    </style>

    <div class="sedoo_related_block_admin_block">
        <h2> Bloc Viewer </h2> <span> Visible seulement en front-office </span>
    </div>

<?php
} else {
?>
    <section class="sedoo-campaign-view-product">
        <?php
        $product_id = get_field('produits_a_afficher');
        $product = get_field('id', $product_id[0]); // get product id and name
        $breadcrumb = get_field('name', $product_id[0]); // get product id and name
        $type_produit = get_field('type', $product_id[0]);

        $campaign = get_option('swc_campaign_name');

        $product_service_urls = get_option("swc_product_service_urls");
        $service_url = $product_service_urls->$type_produit->serviceUrl;
        $package_url = $product_service_urls->$type_produit->packageUrl;

        // selection du type de viewer
        $type_viewer = '';
        switch ($type_produit) {
            case 'calendarbasedproduct':
                break;
            case 'filetree':
                $type_viewer = 'viewer="tree"';
                break;
            case 'wmsproduct':
                $type_viewer = 'viewer="wmts"';
                break;
        }
        ?>
        <campaign-product <?= $type_viewer; ?> service="<?= $service_url; ?>" campaign="<?= $campaign; ?>" product="<?= $product; ?>" breadcrumb='<?= htmlspecialchars($breadcrumb); ?>' vce-ready="">
        </campaign-product>
    </section>
<?php } ?>