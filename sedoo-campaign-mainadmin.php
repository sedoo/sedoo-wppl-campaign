<?php 
function sedoo_main_admin_page_func() { ?>
    <style> <?php include 'css/back.css'; ?> </style>
    <div class="sedoo_admin_bloc">
        <h1> Gestion de la campagne </h1>
        <?php 
        if(!get_field('nom_de_la_campagne', 'option')) { 
        ?>
            <h2>Etape 1 : Initialisation </h2>
            <p> Vous n'avez pas <b> nommé votre campagne </b>, faîtes le pour commencer le paramètrage du site </p>
            <a href="<?php menu_page_url('sedoo-campaign-admin-page'); ?>"> Le faire ici </a>
        <?php 
        }
        if(get_field('nom_de_la_campagne', 'option') && !get_field('id_back_end_campagne', 'option')) { 
            $nom_de_campagne = get_field('nom_de_la_campagne', 'option');
        ?>
            <h2>Etape 2 : Paramètrage du site </h2>
            <p> Nom de campagne : <?php echo $nom_de_campagne; ?> </p>
            <button id="CreateBackEnd" camp_name="<?php echo $nom_de_campagne; ?>" class="button button-primary"> Récupérer l'identifiant BackEnd </button>
        <?php 
        } else { 
            $id_de_campagne = get_field('id_back_end_campagne', 'option');
            $nom_de_campagne = get_field('nom_de_la_campagne', 'option');
            ?>
            <h2 class="green"> Campagne paramétrée <span class="dashicons dashicons-yes-alt"></span></h2>
            <a href="<?php menu_page_url('sedoo-campaign-admin-page'); ?>"> Mes paramètres </a>
            <p> Nom de campagne : <?php echo $nom_de_campagne; ?> </p>
            <p> Identifiant Backend : <?php echo $id_de_campagne; ?> </p>
            <hr />
            <br />
            <?php 
                $id_product_menu = get_field('main-products-campain-menu', 'option');
            ?>
            <button id="SynchroniseProducts" id_backend="<?php echo $id_de_campagne; ?>"  class="button button-primary"> Synchroniser les produits avec le site </button>
            <p> Les nouveaux produits synchronisés seront ajoutés à <a href="<?php echo admin_url().'/nav-menus.php?action=edit&menu='.$id_product_menu; ?>">ce menu</a>
        <?php } ?>
    </div>

    <div class="sedoo_admin_bloc">
    <?php if(get_field('nom_de_la_campagne', 'option')) {?>
        <h2> Administration des produits </h2>
        <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
        <script src="https://services.aeris-data.fr/cdn/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0"></script>
        <input-product-management campaign="<?php echo $nom_de_campagne; ?>"></input-product-management>
    <?php } ?>
    </div>
<?php
echo '<script src="'.plugin_dir_url( __FILE__ ) . 'js/widget_dashboard.js"></script>';
} ?>

          