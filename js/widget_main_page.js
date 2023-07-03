/**
 * Campaign admin step 2
 * Save campaign id, name and settings once they are set (pages to create, etc.)
 *
 */
jQuery("#wp-save-settings").click(function () {
  const idBackend = jQuery(this).attr("wp-id-backend");
  const campaignName = jQuery(this).attr("wp-campaign-name");
  if (idBackend && campaignName) {
    sedoo_campaign_updateOptionMeta("swc_campaign_id", idBackend);
    sedoo_campaign_updateOptionMeta("swc_campaign_name", campaignName);
  }
  // save settings
  const settingsStr = jQuery(this).attr("wp-settings");
  const settings = JSON.parse(settingsStr);
  sedoo_campaign_updateOptionMeta("swc_settings", settings);
  // window.location.reload();
  // sedoo_campaign_sendInfoToSedooRequests({ftp: settings.ftpAccess, login, password }) // tickets
});

/**
 * Campaign admin step 5
 * Save products services urls to wordpress meta once they are set
 */
jQuery("#wp-save-services").click(function () {
  const productsServicesStr = jQuery(this).attr("wp-services");
  const services = JSON.parse(productsServicesStr);
  sedoo_campaign_updateOptionMeta("swc_product_service_urls", services);
});

/**
 * Campaign admin step 4
 * Save products and update products menu
 */
jQuery("#wp-synchronise-products").click(function () {
  const id_backend = jQuery(this).attr("id_backend");

  // check if product menu exist, if not, just recreate it before importation
  jQuery.ajax({
    url: ajaxurl,
    type: "GET",
    data: {
      action: "sedoo_campaign_check_product_menu"
    },
    success: function (success) {
      var products_id_array = [];
      var i = 0;
      jQuery.ajax({
        url:
          "https://api.sedoo.fr/sedoo-campaigns-rest/inputproduct/v1_0/list/" +
          id_backend,
        type: "GET",
        success: function (result) {
          for (i = 0; i < result.length; i++) {
            products_id_array.push(result[i].id);
            sedoo_campaign_createOrUpdateCampaignProduct(result[i]);
          }
          sedoo_campaign_check_for_deleted_product(products_id_array);
        }
      });
    }
  });
});

/**
 * Campaign admin last step
 */
jQuery("#wp-save-campaign").click(function () {
  sedoo_campaign_updateOptionMeta("swc_first_setup_done", true);
});

// checker pour supprimer les produits plus dans le flux
function sedoo_campaign_check_for_deleted_product(productsIdArray) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sedoo_campaign_check_and_delete_missing_products_in_the_flux",
      productsIdArray: productsIdArray
    },
    success: function (result) {}
  });
}

// créer un produit, ou le mettre à jour si il existe déjà
function sedoo_campaign_createOrUpdateCampaignProduct(product) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sedoo_campaign_create_or_update_product",
      product: product
    },
    success: function (result) {}
  });
}

// update une meta de la page "PARAMETRES DE CAMPAGNE"
function sedoo_campaign_updateOptionMeta(metakey, metavalue) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sedoo_campaign_update_option_meta",
      metakey: metakey,
      metavalue: metavalue
    },
    success: function (result) {}
  });
}