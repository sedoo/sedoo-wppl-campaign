<?php

/**
 * Register options in the wp_options table
 */

/**
 * - Name of the campaign,
 * - Backend ID of the campaign,
 * - ID of the products menu,
 * - ID of the campaign main menu,
 * - ID of the data policy page,
 * - ID of the catalogue page,
 * - ID of the catalogue component: @see sedoo-wppl-components @link https://github.com/sedoo/sedoo-wppl-components,
 * - ID of the user manager page,
 * - ID of the user manager component: @see sedoo-wppl-components @link https://github.com/sedoo/sedoo-wppl-components,
 * - ID of the Data Access menu item,
 * - Products archive menu id,
 * - URLs of services and packages by product type: @var object $value
 * - Campaign settings: @var object $value ; handle creation/deletion of pages/components
 * - First setup done: @var bool ; is the campaign fully defined
 */
foreach (SWC_PLUGIN_OPTIONS as $option) {
    add_option($option, '', '', 'no');
}
