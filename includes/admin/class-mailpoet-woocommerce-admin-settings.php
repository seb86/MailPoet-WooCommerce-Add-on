<?php
/**
 * MailPoet WooCommerce Add-on Admin Settings Class.
 *
 * @since    1.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on/Admin
 * @license  GPL-2.0+
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

if( !class_exists('MailPoet_WooCommerce_Admin_Settings')) {

	/**
	 * MailPoet_WooCommerce_Admin_Settings
	 */
	class MailPoet_WooCommerce_Admin_Settings {

		private static $settings = array();

		/**
		 * Include the settings page classes
		 *
		 * @since  1.0.0
		 * @access public static
		 */
		public static function get_settings_pages( ) {
			$settings[] = include( 'settings/class-mailpoet-woocommerce-settings.php' );

			return $settings;
		}

		/**
		 * Save the settings
		 *
		 * @since  1.0.0
		 * @access public static
		 */
		 public static function save() {
			 global $current_section, $current_tab;

			 if ( empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-settings')) {
				 wp_die(__('Action failed. Please refresh the page and retry.', 'mailpoet-woocommerce-add-on'));
			 }
		 }

	 } // END class

} // END if class exists.
