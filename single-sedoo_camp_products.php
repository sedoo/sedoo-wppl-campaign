<?php
get_header();
?>

<div id="content-area" class="main-product wrapper">
    <aside>
        <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
        <script src="https://services.aeris-data.fr/cdn/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0"></script>
        <?php 
            $product_nav_menu_id = get_field('main-products-campain-menu', 'option');
        ?>
        <campaign-product-tree :menu_api_url="'<?php echo home_url(); ?>/wp-json/menus/v1/menus/<?php echo $product_nav_menu_id; ?>'"></campaign-product-tree>
        
    </aside>
    <section class="main">
        <?php 
            while ( have_posts() ) : the_post();
                echo the_content();
            endwhile;
        ?>
    </section>
</div>
<?php 
get_footer();
?>