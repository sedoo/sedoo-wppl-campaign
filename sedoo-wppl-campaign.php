<?php

/**
 * Plugin Name: Sedoo - Campaign Manager
 * Description: Déclarer une campagne, ses produits et les viewers associés
 * Version: 1.1.0
 * Author: Pierre Vert & Nicolas Gruwe 
 * GitHub Plugin URI: sedoo/sedoo-wppl-campaign
 * GitHub Branch: master
 */

include 'inc/sedoo-wppl-campaign-utils.php'; // constants & helpers
include 'inc/sedoo-wppl-campaign-options.php'; // options that the plugin saves to the database
include 'inc/sedoo-wppl-campaign-acf-fields.php';
include 'inc/sedoo-wppl-campaign-menu-json.php';
include 'sedoo-wppl-posttypes.php'; // post types viewers & product
include 'inc/sedoo-wppl-campaign-ajax.php';
include 'sedoo-wppl-campaign-functions.php';
include 'sedoo-wppl-admin.php'; // admin parameters page
