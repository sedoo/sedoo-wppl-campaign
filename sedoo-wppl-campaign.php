<?php

/**
 * Plugin Name: Sedoo - Campaign Manager
 * Description: Configure and manage a campaign, its products and associated viewers
 * Version: 1.1.0
 * Author: Pierre Vert & Nicolas Gruwe 
 * GitHub Plugin URI: sedoo/sedoo-wppl-campaign
 * GitHub Branch: master
 */

include 'inc/sedoo-wppl-campaign-utils.php'; // constants & helpers
include 'inc/sedoo-wppl-campaign-options.php'; // options that the plugin saves to the database
include 'inc/sedoo-wppl-campaign-acf-fields.php'; // acf fields definition
include 'inc/sedoo-wppl-campaign-menu-json.php'; // rest api for nav menus
include 'sedoo-wppl-posttypes.php'; // product post type
include 'inc/sedoo-wppl-campaign-ajax.php'; // all things ajax
include 'sedoo-wppl-campaign-functions.php'; // plugin functions
include 'sedoo-wppl-admin.php'; // admin parameters page and functions
