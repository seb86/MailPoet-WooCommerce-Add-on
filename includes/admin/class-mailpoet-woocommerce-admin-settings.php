<?php
/**
 * MailPoet WooCommerce Add-on Admin Settings Class.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet WooCommerce Add-on/Admin
 * @version 	2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_WooCommerce_Admin_Settings' ) ) {

/**
 * MailPoet_WooCommerce_Admin_Settings
 */
class MailPoet_WooCommerce_Admin_Settings {

	private static $settings = array();

	/**
	 * Include the settings page classes
	 */
	public static function get_settings_pages( ) {
		$settings[] = include( 'settings/class-mailpoet-woocommerce-settings.php' );

		return $settings;
	}

	/**
	 * Save the settings
	 */
	public static function save() {
		global $current_section, $current_tab;

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'woocommerce-settings' ) ) {
			die( __( 'Action failed. Please refresh the page and retry.', 'wc_extend_plugin_name' ) );
		}

	}

}

} // end if class exists.

?>