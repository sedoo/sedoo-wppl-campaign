<?php
// Single product page
get_header();
$product_content = get_the_content();
?>

<div id="content" class="site-content">
    <div id="primary" class="content-area wrapper product_left_menu tocActive">
        <aside>
            <span id="button_fold_menu" class="mdi mdi-arrow-left"></span>
            <campaign-product-tree menu_api_url="<?= MENU_API_URL ?>"></campaign-product-tree>
        </aside>
        <main id="main" class="site-main">
            <div class="wrapper-content">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <section class="sedoo-campaign-view-product">
                        <?php
                        $product = get_field('id'); // get product id and name
                        $breadcrumb = get_field('name'); // get product id and name
                        $product_type = get_field('type');
                        $campaign = get_option('swc_campaign_name');
                        $product_service_urls = get_option("swc_product_service_urls");
                        $service_url = $product_service_urls->$product_type->serviceUrl;
                        $package_url = $product_service_urls->$product_type->packageUrl;

                        // selection du type de viewer
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
                            case 'iframeproduct':
                                $type_viewer = 'viewer="iframe"';
                                break;
                        }
                        ?>
                        <h1> <?php the_title(); ?> </h1>
                        <?php
                        if (!post_password_required()) {
                        ?>
                            <div class="tabs">
                                <?php
                                if ((get_field('sedoo_campaign_product_information_label', get_the_ID())) ||  !empty($product_content)) {
                                ?>
                                    <nav role="tablist" aria-label="Description and information">

                                        <button role="tab" aria-selected="true" aria-controls="visu" id="tab-1" tabindex="0">
                                            Visualisation
                                        </button>
                                        <button role="tab" aria-selected="false" aria-controls="sedoo_campaign_product_description" id="tab-2" tabindex="-1">
                                            <?php
                                            if (get_field('sedoo_campaign_product_description_label', get_the_ID())) {
                                                the_field('sedoo_campaign_product_description_label', get_the_ID());
                                            } else {
                                                echo 'Description';
                                            }
                                            ?>
                                        </button>
                                        <button role="tab" aria-selected="false" aria-controls="sedoo_campaign_product_information" id="tab-3" tabindex="-1">
                                            <?php
                                            if (get_field('sedoo_campaign_product_information_label', get_the_ID())) {
                                                the_field('sedoo_campaign_product_information_label', get_the_ID());
                                            } else {
                                                echo 'Details';
                                            }
                                            ?>
                                        </button>

                                    </nav>
                                <?php
                                }
                                ?>
                                <section id="visu" role="tabpanel" tabindex="0" aria-labelledby="tab-1">
                                    <campaign-product <?= $type_viewer; ?> service="<?= $service_url; ?>" campaign="<?= $campaign; ?>" product="<?= $product; ?>" breadcrumb='<?= $breadcrumb; ?>' vce-ready="">
                                    </campaign-product>
                                </section>
                                <section id="sedoo_campaign_product_description" role="tabpanel" tabindex="0" aria-labelledby="tab-2" hidden>
                                    <?= $product_content; ?>
                                </section>
                                <section id="sedoo_campaign_product_information" role="tabpanel" tabindex="0" aria-labelledby="tab-2" hidden>
                                    <?= get_field('sedoo_campaign_product_information', get_the_ID()); ?>
                                </section>

                            </div>
                            <?php
                            // if ( ! post_password_required() ) {
                            ?>

                        <?php
                        } else {
                            the_content();
                        }
                        ?>
                    </section>
                </article>
            </div>
        </main>
        <!-- #main -->
    </div>
    <!-- #primary -->
</div>

<?php
get_footer();
