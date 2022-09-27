<?php
// /products page
get_header();
?>

<div id="content" class="site-content">
	<div id="primary" class="content-area wrapper product_left_menu tocActive">
		<aside>
            <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
            <script src="https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0"></script>
            <?php 
                $product_nav_menu_id = get_field('main-products-campain-menu', 'option');
            ?>
            <campaign-product-tree menu_api_url="<?php echo home_url(); ?>/wp-json/menus/v1/menus/<?php echo $product_nav_menu_id; ?>"></campaign-product-tree>
		</aside>
		<main id="main" class="site-main">
			<div class="wrapper-content">
				<article id="post-" <?php post_class(); ?>>
                   <p>CHOOSE YOUR PRODUCT ON THE LEFT</p>
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