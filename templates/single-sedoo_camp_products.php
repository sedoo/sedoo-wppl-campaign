<?php
// Single product page
get_header();
?>


<div id="content" class="site-content">
	<div id="primary" class="content-area wrapper product_left_menu tocActive">
		<aside>
            <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
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
                            $product = get_field('id');
                            $breadcrumb = get_field('id');
                            $campaign = get_field('nom_de_la_campagne', 'option');   

                            // selection du type de viewer
                            $type_viewer = get_field('type');
                            $attribut_viewer = '';
                            switch ($type_viewer) {
                                case 'calendarbasedproduct':
                                    break;
                                case 'filetree':
                                    $attribut_viewer= 'viewer="tree"';
                                    break;
                            }
                        ?>
                        <campaign-product <?php echo $attribut_viewer; ?> service="https://services.aeris-data.fr/campaigns/data/v1_0" campaign="<?php echo $campaign; ?>" product="<?php echo $product; ?>" breadcrumb="<?php echo $breadcrumb; ?>" vce-ready="">
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