<?php
if(is_admin() == true) {
    ///////
    // Pas besoin de montrer le composant si je suis en Admin, juste dire qu'il est la
    //////
    ?>
    <style>
    div.sedoo_related_block_admin_block {
        text-align: center;
        background: #c8c8c8;
        color:#535050;
        padding: 15px;
        padding-top: 1px;
    }
    </style>
    <?php 
        echo '<div class="sedoo_related_block_admin_block"><h2> Bloc Viewer Misva </h2> <span> Visible seulement en front-office </span></div>';
} else {
        ////////
        // Je crée ma classe pour l'alignement wordpress etc
        ////////
        $className = '';
        if( !empty($block['className']) ) {
            $className .= ' ' . $block['className'];
        }
        if( !empty($block['align']) ) {
            $className .= ' align' . $block['align'];
        }

        ////////
        // Je recupuère le viewer utilisé dans le bloc
        ////////
        $viewer_misva = get_field('type_de_viewer_a_charger');

        
        ////////
        // Je récupère les lignes de scripts (css et js) du viewer et les affiche
        ///////
        if( have_rows('elements_inclus_misva', $viewer_misva[0]) ):
            while( have_rows('elements_inclus_misva', $viewer_misva[0]) ): the_row(); 
                echo the_sub_field('script_misva', $viewer_misva[0]);
            endwhile; 
        endif;
        
        
        ////////
        // Je récupère les produits dans le champs AINSI que leur breadcrumb (title)
        ///////
        $produits = get_field('produits_a_afficher');
        $listing_produit = '';
        $listing_breadcrumb = '';
        foreach($produits as $produit) {
            // stocker le nom
            $listing_breadcrumb .= $produit['label'].',';
            // stocker l'id
            $listing_produit .= $produit['value'].',';
        }
        $listing_breadcrumb = substr($listing_breadcrumb,0,-1);        
        $listing_produit = substr($listing_produit,0,-1);

        ////////
        // Je crée la chaîne d'attributs/valeurs du viewer qui seront utilisés
        ///////
        $string_construct_attributes = '';
        if( have_rows('repeteur_attributs_misva', $viewer_misva[0]) ):
            while( have_rows('repeteur_attributs_misva', $viewer_misva[0]) ): the_row(); 
                $string_construct_attributes .= get_sub_field('nom_de_lattribut', $viewer_misva[0]);
                $string_construct_attributes .= '="'.get_sub_field('valeur_de_lattribut', $viewer_misva[0]).'"';
            endwhile; 
        endif;


        ////////
        // Je stock le nom de la balise du viewer
        ///////
        $nom_balise = get_field('nom_de_la_balise', $viewer_misva[0]);

        ?>

        <!--////////
        // J'afiche la section composant Misva avec les attributs et la classe
        ///////-->
        <section class="sedoo-misva-component <?php echo $className;?>">
            <?php echo '<'.$nom_balise.' '.$string_construct_attributes.' product="'.$listing_produit.'" breadcrumb="'.htmlspecialchars($listing_breadcrumb).'"> </'.$nom_balise.'>'; ?>
        </section>
<?php } ?>
