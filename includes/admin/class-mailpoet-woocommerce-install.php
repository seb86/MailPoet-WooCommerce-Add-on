<?php
/**
 * Installation related functions and actions.
 *
 * @class    MailPoet_WooCommerce_Add_On_Install
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @license  GPL-2.0+
 * @since    1.0.0
 * @version  4.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('MailPoet_WooCommerce_Add_On_Install' ) ) {

	class MailPoet_WooCommerce_Add_On_Install {

		/**
		 * Constructor
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 4.0.0
		 */
		public function __construct() {
			register_activation_hook( MailPoet_WooCommerce_Add_On::plugin_filename(), array( $this, 'install' ) );
		}

		/**
		 * Install MailPoet WooCommerce Add-on
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 4.0.0
		 */
		public function install() {
			$this->create_options();

			// Add plugin version
			update_option( 'mailpoet_woocommerce_addon_version', MailPoet_WooCommerce_Add_On::$version );
		}

		/**
		 * Sets up the default options for the settings page.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function create_options() {
			// Include settings so that we can run through defaults.
			include_once( MailPoet_WooCommerce_Add_On::plugin_path() . '/includes/admin/class-mailpoet-woocommerce-admin-settings.php' );

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
		 * @access public
		 * @since  3.0.0
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

return new MailPoet_WooCommerce_Add_On_Install();
