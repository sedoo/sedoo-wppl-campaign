<?php 
function sedoo_main_admin_page_func() { // campaign main page?>
    <div class="sedoo_admin_bloc">
        <h1> Gestion de la campagne </h1>
        <?php 
        if(!get_field('nom_de_la_campagne', 'option')) { 
        ?>
            <h2>Etape 1 : Initialisation </h2>
            <p> Vous n'avez pas <b> nommé votre campagne </b>, faîtes le pour commencer le paramètrage du site </p>
            <a href="<?php menu_page_url('sedoo-campaign-admin-page'); ?>"> Le faire ici </a>
        <?php 
        } else {
            if(!get_field('id_back_end_campagne', 'option')) { 
                $nom_de_campagne = get_field('nom_de_la_campagne', 'option');
            ?>
                <h2>Etape 2 : Paramètrage du site </h2>
                <p> Nom de campagne : <?php echo $nom_de_campagne; ?> </p>
                <button id="CreateBackEnd" camp_name="<?php echo $nom_de_campagne; ?>" class="button button-primary"> Récupérer l'identifiant BackEnd </button>
            <?php 
            }
        } 
        if(get_field('nom_de_la_campagne', 'option') && get_field('id_back_end_campagne', 'option')) { 
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

    <?php if(get_field('nom_de_la_campagne', 'option') && get_field('id_back_end_campagne', 'option')) {?>
        <ul class="campaign_param_tabs_main"> 
            <li class="admin_tabs_button active" sedoo_campaign_tab="admin_camp"> Gestion de la campagne </li>
            <li class="admin_tabs_button" sedoo_campaign_tab="admin_prod"> Gestion des produits </li>
            <li class="admin_tabs_button noclick suppage"><a href="admin.php?page=sedoo-campaign-admin-page"> Paramètres</a> </li>
        </ul>
        <div class="sedoo_admin_bloc tabbed">
            <div class="tab_admin_camp tab_content admin_camp active">
                <campaign-metadata uuid="<?php echo $nom_de_campagne; ?>" ></campaign-metadata>
            </div>
            <div class="tab_admin_prod tab_content admin_prod">
                <h2> Administration des produits </h2>
                <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
                <script src="https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0"></script>
                <input-product-management campaign="<?php echo $nom_de_campagne; ?>"></input-product-management>
            </div>
        </div>
    <?php } ?>
<?php
echo '<script src="'.plugin_dir_url( __FILE__ ) . 'js/widget_main_page.js"></script>';
} ?>

          