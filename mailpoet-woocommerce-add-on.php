<?php
/*
 * Plugin Name: MailPoet Checkout Subscription for WooCommerce - Legacy
 * Plugin URI:  https://wordpress.org/plugins/mailpoet-woocommerce-add-on
 * Version:     4.0.1
 * Description: Let your customers subscribe to your newsletter as they checkout with their purchase.
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 *
 * Text Domain: mailpoet-woocommerce-add-on
 * Domain Path: /languages/
 *
 * Requires at least: 4.7
 * Tested up to: 4.9
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.5
 *
 * Copyright: © 2017 Sébastien Dumont
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! class_exists( 'WC_Dependencies' ) ) {
	require_once( 'woo-dependencies/woo-dependencies.php' );
}

// Quit right now if WooCommerce is not active
if ( ! is_woocommerce_active() ) {
	return;
}

if ( ! class_exists( 'MailPoet_WooCommerce_Add_On' ) ) {

	class MailPoet_WooCommerce_Add_On {

		/**
		 * @var - MailPoet_WooCommerce_Add_On - The single instance of the class.
		 *
		 * @access protected
		 * @static
		 * @since  2.0.0
		 */
		protected static $_instance = null;

		/**
		 * Plugin Version
		 *
		 * @access public
		 * @static
		 * @since  4.0.0
		 */
		public static $version = '4.0.0';

		/**
		 * Required WooCommerce Version
		 *
		 * @access public
		 * @since  4.0.0
		 */
		public $required_woo = '3.0.0';

		/**
		 * Main MailPoet Checkout Subscription for WooCommerce (Legacy) Instance
		 *
		 * Ensures only one instance of MailPoet Checkout Subscription for WooCommerce (Legacy) is loaded or can be loaded.
		 *
		 * @access public
		 * @static
		 * @since   1.0.0
		 * @version 4.0.0
		 * @see     MailPoet_WooCommerce_Add_on()
		 * @return  MailPoet_WooCommerce_Add_on - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @access public
		 * @since  3.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin’ huh?', 'mailpoet-woocommerce-add-on' ) );
		} // END __clone()

		/**
		 * Unserializing instances of the class is forbidden.
		 *
		 * @since  3.0.0
		 * @access public
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin’ huh?', 'mailpoet-woocommerce-add-on') );
		} // END __wakeup()

		/**
		 * Load the plugin.
		 *
		 * @access public
		 * @since  3.0.0
		 */
		public function __construct(){
			add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
			add_action( 'init', array( $this, 'init_plugin' ) );

			// Include required files
			add_action( 'woocommerce_loaded', array( $this, 'includes' ) );
		}

		/*-----------------------------------------------------------------------------------*/
		/*  Helper Functions                                                                 */
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Get the Plugin URL.
		 *
		 * @access public
		 * @static
		 * @since  4.0.0
		 */
		public static function plugin_url() {
			return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		} // END plugin_url()

		/**
		 * Get the Plugin Path.
		 *
		 * @access public
		 * @static
		 * @since  4.0.0
		 */
		public static function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		} // END plugin_path()

		/**
		 * Get the Plugin Base path name.
		 *
		 * @access public
		 * @static
		 * @since  4.0.0
		 * @return string
		 */
		public static function plugin_basename() {
			return plugin_basename( __FILE__ );
		} // END plugin_basename()

		/**
		 * Get the Plugin File name.
		 *
		 * @access public
		 * @static
		 * @since  4.0.0
		 * @return string
		 */
		public static function plugin_filename() {
			return __FILE__;
		} // END plugin_filename()

		/*-----------------------------------------------------------------------------------*/
		/*  Load Files                                                                       */
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Check requirements on activation.
		 *
		 * @access public
		 * @since  4.0.0
		 */
		public function load_plugin() {
			// Check we're running the required version of WooCommerce.
			if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, $this->required_woo, '<' ) ) {
				add_action( 'admin_notices', array( $this, 'wc_mailpoet_admin_notice' ) );
				return false;
			}

			// Check we're running MailPoet 2
			if ( ! in_array( 'wysija-newsletters/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_action( 'admin_notices', array( $this, 'requirement_mailpoet_notice' ), 10 );
				return false;
			}
		} // END load_plugin()

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function includes() {
			include_once( 'includes/mailpoet-woocommerce-core-functions.php' ); // Contains core functions for the front/back end.
			include_once( 'includes/mailpoet-woocommerce-hooks.php' ); // Hooks used at the frontend.

			if ( is_admin() ) {
				include_once( 'includes/admin/class-mailpoet-woocommerce-install.php' ); // Install plugin
				include_once( 'includes/admin/class-mailpoet-woocommerce-admin.php' ); // Admin section
			}
		} // END includes()

		/**
		 * Display a warning message if minimum version of WooCommerce check fails.
		 *
		 * @access public
		 * @since  4.0.0
		 * @return void
		 */
		public function wc_mailpoet_admin_notice() {
			echo '<div class="error"><p>' . sprintf( __( '%1$s requires at least %2$s v%3$s in order to function. Please upgrade %2$s.', 'mailpoet-woocommerce-add-on' ), 'MailPoet Checkout Subscription for WooCommerce (Legacy)', 'WooCommerce', $this->required_woo ) . '</p></div>';
		} // END wc_mailpoet_wc_admin_notice()

		/**
		 * Displays a warning message if MailPoet 2 is not active or installed.
		 *
		 * @access  public
		 * @since   3.0.0
		 * @version 4.0.0
		 */
		public function requirement_mailpoet_notice() {
			echo '<div class="error"><p>' . sprintf( __( 'Hold on a minute. You need to <a href="' . admin_url( '/plugin-install.php?s=MailPoet+2&tab=search&type=term' ) . '">install MailPoet 2</a> first to use <strong>%s</strong>.', 'mailpoet-woocommerce-add-on' ), 'MailPoet Checkout Subscription for WooCommerce (Legacy)' ) . '</p></div>';
		} // END requirement_mailpoet_notice()

		/*-----------------------------------------------------------------------------------*/
		/*  Localization                                                                     */
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Initialize the plugin if ready.
		 *
		 * @access public
		 * @since  4.0.0
		 * @return void
		 */
		public function init_plugin() {
			// Load text domain.
			load_plugin_textdomain( 'mailpoet-woocommerce-add-on', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} // END init_plugin()

	} // END class

} // END if class exists

return MailPoet_WooCommerce_Add_On::instance();
