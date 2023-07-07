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
					<!-- <p>Choose a product on the left to visualize it</p> -->
					<div role="alert" class="v-alert v-sheet theme--dark v-alert--border v-alert--border-top" style="margin: 12px; background: var(--theme-color);">
						<div class="v-alert__wrapper">
							<div class="v-alert__content">
								<span class="mdi mdi-arrow-left"></span>
								Select a product on the left to visualize it here
							</div>
							<div class="v-alert__border v-alert__border--top"></div>
						</div>
					</div>
				</article>
			</div>
		</main>
		<!-- #main -->
	</div>
	<!-- #primary -->
</div>

<?php
get_footer();
