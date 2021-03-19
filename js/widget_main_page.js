


// ON CLICK GET BACKEND ID
jQuery('#CreateBackEnd').click(function() {
    jQuery(this).prop('disabled', true);
    jQuery(this).text('Loading..');
    var campaign_name = jQuery(this).attr('camp_name');
    var backEndId;
    // CHECK SI LE BACKEND EXISTE DEJA
    jQuery.ajax({
        url: 'https://services.aeris-data.fr/campaigns/campaign/v1_0/findbyname/'+campaign_name,
        type:'GET',
        success:function(result) {
            if(result == '') {
                    // si backend non existant je le crée
                    jQuery.ajax({
                        url: 'https://services.aeris-data.fr/campaigns/campaign/v1_0/save',
                        type:'POST',        
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({ 
                            "campaignName" : campaign_name
                        }),
                        success:function(result) {
                            // et j'enregistre l'id du backend
                            backEndId = result.id;
                        }
                    });
            }
            // sinon j'enregistre l'id du backend directement 
            else {
                backEndId = result.id;
            }
            sedoo_campaign_updateOptionMeta('id_back_end_campagne', backEndId);
            jQuery('#CreateBackEnd').text('BackEnd Ok');
            jQuery('#CreateBackEnd').after('<a class="button button-primary nextstpbtn" href="admin.php?page=sedoo-campaign-admin-main-page">Etape suivante</a>');
        }
    });
});

// ON CLICK SYNCHRONIZE PRODUCTS
jQuery('#SynchroniseProducts').click(function() {

    // check if product menu exist, if not, just recreate it before importation
    jQuery.ajax({
        url: ajaxurl,
        type:'GET',
        data: { 
            action : 'sedoo_cmpaign_check_product_menu',
        },
        success:function(result) {
        }
    });
    var products_id_array = [];
    var id_backend = jQuery(this).attr('id_backend');
    jQuery(this).prop('disabled', true);
    jQuery(this).text('Loading..');
    var i=0;
    jQuery.ajax({
        url: 'https://services.aeris-data.fr/campaigns/inputproduct/v1_0/list/'+id_backend,
        type:'GET',
        success:function(result) {
            for(i;i < result.length; i++) {
                sedoo_campaign_createOrUpdateCampaignProduct(result[i], ajaxurl, i, result.length);
                products_id_array.push(result[i].id);
            }
            sedoo_campaign_check_for_deleted_product(products_id_array);
        },
        complete:function(result) {
        }
    }); 
})

// Les fonctions utilisées pour apeller les fonctions php

// checker pour supprimer les produits plus dans le flux
function sedoo_campaign_check_for_deleted_product(productsIdArray) {
    jQuery.ajax({
        url: ajaxurl,
        type:'POST',
        data: { 
            action : 'sedoo_campaign_check_and_delete_missing_products_in_the_flux',
            'productsIdArray':productsIdArray
        },
        success:function(result) {
        }
    });
}

// créer un produit, ou le mettre à jour si il existe déjà
function sedoo_campaign_createOrUpdateCampaignProduct(product, ajaxurl, i, total) {
    jQuery.ajax({
        url: ajaxurl,
        type:'POST',
        data: { 
            action : 'sedoo_campaign_create_or_update_product',
            'product':product
        },
        success:function(result) {
            jQuery("#SynchroniseProducts").text((i+1) +'/'+ total+ ' Synchronisé(s) !');
        }
    });
}


// update une meta de la page "PARAMETRES DE CAMPAGNE"
function sedoo_campaign_updateOptionMeta(metakey, metavalue) {
    jQuery.ajax({
        url: ajaxurl,
        type:'POST',
        data: { 
            action : 'sedoo_campaign_update_option_meta',
            'metakey':metakey,
            'metavalue':metavalue
        },
        success:function(result) {
        }
    });
}


// On click on the tabs on the admin page
jQuery('.admin_tabs_button').click(function() {
    if(jQuery(this).hasClass('noclick')) {} 
    else {
        var tab_active_class = jQuery(this).attr('sedoo_campaign_tab');
        console.log(tab_active_class);
        // les tabs
        jQuery('.admin_tabs_button').removeClass('active');
        jQuery(this).addClass('active');
        // le contenun des tabs
        jQuery('.tab_content').removeClass('active');
        jQuery('.'+tab_active_class).toggleClass('active');
    }
});
