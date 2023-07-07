<?php
if (is_admin() == true) {
    ///////
    // No need to display the component on admin side
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
        <h2> Viewer Block </h2> <span> Only visible on the front side </span>
    </div>

<?php
} else {
?>
    <section class="sedoo-campaign-view-product">
        <?php
        $product_id = get_field('products_to_display');
        $product = get_field('id', $product_id[0]); // get product id and name
        $breadcrumb = get_field('name', $product_id[0]); // get product id and name
        $product_type = get_field('type', $product_id[0]);

        $campaign = get_option('swc_campaign_name');

        $product_service_urls = get_option("swc_product_service_urls");
        $service_url = $product_service_urls->$product_type->serviceUrl;
        $package_url = $product_service_urls->$product_type->packageUrl;

        // Viewer type selection
        $type_viewer = '';
        switch ($product_type) {
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