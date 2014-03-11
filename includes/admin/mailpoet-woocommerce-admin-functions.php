<?php
/**
 * MailPoet WooCommerce Add-on Admin Functions
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet WooCommerce Add-on/Admin/Functions
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get all MailPoet WooCommerce Add-on screen ids
 *
 * @return array
 */
function mailpoet_woocommerce_get_screen_ids() {
	$menu_name = strtolower( str_replace ( ' ', '-', MAILPOET_WOOCOMMERCE_PAGE ) );

	$mailpoet_woocommerce_screen_id = strtolower( str_replace ( ' ', '-', __( 'MailPoet WooCommerce Add-on', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) ) );

	return apply_filters( 'mailpoet_woocommerce_screen_ids', array(
		'toplevel_page_' . $mailpoet_woocommerce_screen_id,
		'woocommerce_page_wc_settings',
		'woocommerce_page_wc-settings',
		'woocommerce_page_woocommerce_settings',
		'woocommerce_page_woocommerce-settings'
	) );
}

/**
 * Get a setting from the settings API.
 *
 * @param mixed $option
 * @return string
 */
function mailpoet_woocommerce_settings_get_option( $option_name, $default = '' ) {
	if ( ! class_exists( 'MailPoet_WooCommerce_Admin_Settings' ) ) {
		include 'class-mailpoet-woocommerce-admin-settings.php';
	}

	return MailPoet_WooCommerce_Admin_Settings::get_option( $option_name, $default );
}

?>