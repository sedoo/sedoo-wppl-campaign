<?php
// Single product page
get_header();
?>


<div id="content" class="site-content">
	<div id="primary" class="content-area wrapper product_left_menu tocActive">
		<aside>
            <script src="https://services.aeris-data.fr/cdn/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0"></script>
            <?php 
                $product_nav_menu_id = get_field('main-products-campain-menu', 'option');
            ?>
            <campaign-product-tree menu_api_url="<?php echo home_url(); ?>/wp-json/menus/v1/menus/<?php echo $product_nav_menu_id; ?>"></campaign-product-tree>
		</aside>
		<main id="main" class="site-main">
			<div class="wrapper-content">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
                    <?php 
                        while ( have_posts() ) : the_post();
                           echo the_content();
                        endwhile;
                    ?>
                    <section class="sedoo-campaign-view-product">
                        <?php 
                            $product = get_field('id'); // get product id and name
                            $breadcrumb = get_field('name'); // get product id and name
                            $type_produit = get_field('type');

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
                        <campaign-product <?php echo $type_viewer; ?> service="<?php echo $service_url; ?>" campaign="<?php echo $campaign; ?>" product="<?php echo $product; ?>" breadcrumb='<?php echo $breadcrumb; ?>' vce-ready="">
                        </campaign-product>
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
?>