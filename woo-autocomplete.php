<?php
/**
 * Plugin Name: Woo-Autocomplete
 * Plugin URI: http://www.conceptualapps.com/
 * Description: A wordpress plugin that works with woocommerce for auto filling billing/shipping address and hence increase sales
 * Version: 1.0.0
 * Author: Conceptualapps
 * Author URI: http://www.surajkpblog.wordpress.com/
 * Requires at least: 4.1
 * Tested up to: 4.6
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */ 
global $woo_ac_plugin_dir;

$woo_ac_plugin_dir = dirname(__FILE__) . "/";

include_once $woo_ac_plugin_dir.'includes/wooautocomplete.php';

new wooautocomplete();
?>
