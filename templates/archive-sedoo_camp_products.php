<?php
// products page
get_header();
?>

<div id="content" class="site-content">
	<div id="primary" class="content-area wrapper product_left_menu tocActive">
		<aside>
			<campaign-product-tree menu_api_url="<?= MENU_API_URL ?>"></campaign-product-tree>
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
