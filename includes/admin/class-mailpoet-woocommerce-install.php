<?php
/**
 * Installation related functions and actions.
 *
 * @since    1.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @version  3.0.0
 * @license  GPL-2.0+
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

if(! class_exists('MailPoet_WooCommerce_Install')){

	/**
 	 * MailPoet_WooCommerce_Install Class
 	 *
 	 * @since 1.0.0
 	 */
	class MailPoet_WooCommerce_Install {

		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			register_activation_hook( MAILPOET_WOOCOMMERCE_FILE, array($this, 'install') );
		}

		/**
		 * Install MailPoet WooCommerce Add-on
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function install() {
			$this->create_options();

			// Add plugin version
			update_option( 'mailpoet_woocommerce_addon_version', MAILPOET_WOOCOMMERCE_VERSION );
		}

		/**
		 * Sets up the default options for the settings page.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function create_options() {
			// Include settings so that we can run through defaults.
			include_once( 'class-mailpoet-woocommerce-admin-settings.php' );

			$settings = MailPoet_WooCommerce_Add_On_Settings::get_settings();

			// Run through each section and settings to load the default settings.
			foreach ( $settings as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		} // END create_options()

		/**
		 * Delete all plugin options.
		 *
		 * @since  3.0.0
		 * @access public
		 * @global $wpdb
		 * @return void
		 */
		public function delete_options() {
			global $wpdb;

			// Delete options
			$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'mailpoet_woocommerce_%';" );
		} // END delete_options()

	} // END if class.

} // END if class exists.

return new MailPoet_WooCommerce_Install();
