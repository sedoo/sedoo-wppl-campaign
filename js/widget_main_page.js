/**
 * Handle actions on the campaign manager admin page
 * to update the campaign parameters
 */

/**
 * Campaign admin step 2
 * Save campaign id, name and settings once they are set (pages to create, etc.)
 *
 */
jQuery("#wp-save-settings").click(function () {
  const idBackend = jQuery("#wp-campaign-id-field").attr("data-wp");
  const campaignName = jQuery("#wp-campaign-name-field").attr("data-wp");

  if (idBackend && campaignName) {
    sedoo_campaign_updateOptionMeta("swc_campaign_id", idBackend);
    sedoo_campaign_updateOptionMeta("swc_campaign_name", campaignName);
  }
  // save settings
  const settingsStr = jQuery(this).attr("wp-settings");
  const settings = JSON.parse(settingsStr);
  sedoo_campaign_updateOptionMeta("swc_settings", settings);
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
jQuery("#wp-synchronise-products").ready(function () {
  jQuery("#wp-synchronise-products").click(function () {
    const id_backend = jQuery(this).attr("id_backend");

    // check if product menu exist, if not, just recreate it before importation
    jQuery.ajax({
      url: ajaxurl,
      type: "GET",
      data: {
        action: "sedoo_campaign_check_product_menu"
      },
      success: function (response) {
        const data = JSON.parse(response);
        var products_id_array = [];
        var i = 0;
        if (data.status === "success") {
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
      }
    });
  });
});

/**
 * Campaign admin last step
 */
jQuery("#wp-save-campaign").click(function () {
  sedoo_campaign_updateOptionMeta("swc_first_setup_done", true, true);
});

/**
 * Delete missing products
 * 
 * @param {array} productsIdArray 
 */
function sedoo_campaign_check_for_deleted_product(productsIdArray) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sedoo_campaign_check_and_delete_missing_products_in_the_flux",
      productsIdArray: productsIdArray
    },
    success: function () {}
  });
}

/**
 * Create or update a product
 * 
 * @param {string} product 
 */
function sedoo_campaign_createOrUpdateCampaignProduct(product) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sedoo_campaign_create_or_update_product",
      product: product
    },
    success: function () {}
  });
}

/**
 * Update campaign option
 * 
 * @param {string} metakey 
 * @param {mixed} metavalue 
 * @param {boolean} finalSave 
 */
function sedoo_campaign_updateOptionMeta(
  metakey,
  metavalue,
  finalSave = false
) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sedoo_campaign_update_option_meta",
      metakey: metakey,
      metavalue: metavalue
    },
    success: function () {
      const attr = jQuery("sedoocampaigns-admin").attr("first-setup-done");
      // do not reload is first setup is not done
      if ((typeof attr !== "undefined" && attr !== false) || finalSave) {
        // on success, reload page to take into account new option values
        document.location.reload(true);
      }
    }
  });
}
