<?php
/**
 * Runs on Uninstall of MailPoet WooCommerce Add-On
 *
 * @since    3.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  MailPoet WooCommerce Add-On
 * @license  GPL-2.0+
 */
if( ! defined('WP_UNINSTALL_PLUGIN')) exit();

global $wpdb;

// For a single site
if(! is_multisite()) {
	$uninstall = get_option('mailpoet_woocommerce_uninstall_data');

	if(! empty($uninstall)) {
		// Delete options
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'mailpoet_woocommerce_%';");
	}
}
