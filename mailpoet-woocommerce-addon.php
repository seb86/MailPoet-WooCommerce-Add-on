<?php
/*
 * Plugin Name: MailPoet WooCommerce Add-on
 * Plugin URI: http://www.mailpoet.com
 * Description: Adds a checkbox for your customers to subscribe to your MailPoet newsletters during checkout.
 * Version: 2.0.0
 * Author: Sebs Studio
 * Author URI: http://www.sebs-studio.com
 * Author Email: sebastien@sebs-studio.com
 * Requires at least: 3.7.1
 * Tested up to: 3.8.1
 *
 * Text Domain: mailpoet_woocommerce
 * Domain Path: /languages/
 * Network: false
 *
 * Copyright: (c) 2014 Sebs Studio. (sebastien@sebs-studio.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package MailPoet_WooCommerce_Add_on
 * @author Sebs Studio
 * @category Core
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Main MailPoet WooCommerce Add-on Class
 *
 * @class MailPoet_WooCommerce_Add_on
 * @version 2.0.0
 */
final class MailPoet_WooCommerce_Add_on {

	/**
	 * Constants
	 */

	// Slug
	const slug = 'mailpoet_woocommerce_add_on';

	// Text Domain
	const text_domain = 'mailpoet_woocommerce';

	// Name
	const name = 'MailPoet WooCommerce Add-on';

	/**
	 * Global Variables
	 */

	/**
	 * The Plug-in name.
	 *
	 * @var string
	 */
	public $name = "MailPoet WooCommerce Add-on";

	/**
	 * The Plug-in version.
	 *
	 * @var string
	 */
	public $version = "2.0.0";

	/**
	 * The WordPress version the plugin requires minimum.
	 *
	 * @var string
	 */
	public $wp_version_min = "3.7.1";

	/**
	 * The WooCommerce version this extension requires minimum.
	 *
	 * @var string
	 */
	public $woo_version_min = "2.0.20";

	/**
	 * The single instance of the class
	 *
	 * @var MailPoet WooCommerce Add-on
	 */
	protected static $_instance = null;

	/**
	 * The Plug-in URL.
	 *
	 * @var string
	 */
	public $web_url = "http://www.mailpoet.com/";

	/**
	 * The Plug-in documentation URL.
	 *
	 * @var string
	 */
	public $doc_url = "https://github.com/seb86/MailPoet-WooCommerce-Add-on/wiki/";

	/**
	 * The WordPress Plug-in URL.
	 *
	 * @var string
	 */
	public $wp_plugin_url = "http://wordpress.org/plugins/mailpoet-woocommerce-add-on";

	/**
	 * The WordPress Plug-in Support URL.
	 *
	 * @var string
	 */
	public $wp_plugin_support_url = "http://wordpress.org/support/plugin/mailpoet-woocommerce-add-on";

	/**
	 * GitHub Username
	 *
	 * @var string
	 */
	public $github_username = "seb86";

	/**
	 * GitHub Repo URL
	 *
	 * @var string
	 */
	public $github_repo_url = "https://github.com/username/MailPoet-WooCommerce-Add-on/";

	/**
	 * The Plug-in manage woocommerce.
	 *
	 * @var string
	 */
	public $manage_plugin = "manage_woocommerce";

	/**
	 * Main MailPoet WooCommerce Add-on Instance
	 *
	 * Ensures only one instance of MailPoet WooCommerce Add-on is loaded or can be loaded.
	 *
	 * @access public static
	 * @see MailPoet_WooCommerce_Add_on()
	 * @return MailPoet WooCommerce Add-on - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct(){
		// Define constants
		$this->define_constants();

		// Check plugin requirements
		$this->check_requirements();

		// Include required files
		$this->includes();

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, 'action_links' ) );
		add_action( 'init', array( &$this, 'init_mailpoet_woocommerce_add_on' ), 0 );
	}

	/**
	 * Define Constants
	 *
	 * @access private
	 */
	private function define_constants() {
		define( 'MAILPOET_WOOCOMMERCE', $this->name );
		define( 'MAILPOET_WOOCOMMERCE_FILE', __FILE__ );
		define( 'MAILPOET_WOOCOMMERCE_VERSION', $this->version );
		define( 'MAILPOET_WOOCOMMERCE_WP_VERSION_REQUIRE', $this->wp_version_min );
		define( 'MAILPOET_WOOCOMMERCE_WOO_VERSION_REQUIRE', $this->woo_version_min );
		define( 'MAILPOET_WOOCOMMERCE_PAGE', str_replace('_', '-', self::slug) );
		define( 'MAILPOET_WOOCOMMERCE_TEXT_DOMAIN', str_replace('_', '-', self::slug) );

		define( 'MAILPOET_WOOCOMMERCE_README_FILE', 'http://plugins.svn.wordpress.org/mailpoet-woocommerce-add-on/trunk/readme.txt' );

		define( 'GITHUB_USERNAME', $this->github_username );
		define( 'GITHUB_REPO_URL' , str_replace( 'username', GITHUB_USERNAME, $this->github_repo_url ) );

		$woo_version_installed = get_option('woocommerce_version');
		define( 'MAILPOET_WOOVERSION', $woo_version_installed );
	}

	/**
	 * Plugin action links.
	 *
	 * @access public
	 * @param mixed $links
	 * @param string $file plugin file path and name being processed
	 * @return void
	 */
	public function action_links( $links ) {
		// List your action links
		if( current_user_can( $this->manage_plugin ) ) {
			if(version_compare(MAILPOET_WOOVERSION, "2.0.20", '<=')){
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=woocommerce_settings&tab=' . MAILPOET_WOOCOMMERCE_PAGE ) . '">' . __( 'Settings', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a>',
				);
			}
			else{
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . MAILPOET_WOOCOMMERCE_PAGE ) . '">' . __( 'Settings', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a>',
				);
			}
		}

		return array_merge( $links, $plugin_links );
	}

	/**
	 * Checks that the WordPress setup meets the plugin requirements.
	 *
	 * @access private
	 * @global string $wp_version
	 * @return boolean
	 */
	private function check_requirements() {
		global $wp_version, $woocommerce;

		require_once(ABSPATH.'/wp-admin/includes/plugin.php');

		if (!version_compare($wp_version, MAILPOET_WOOCOMMERCE_WP_VERSION_REQUIRE, '>=')) {
			add_action('admin_notices', array( &$this, 'display_req_notice' ) );
			return false;
		}

		if( function_exists( 'is_plugin_active' ) ) {
			if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				add_action('admin_notices', array( &$this, 'display_req_woo_not_active_notice' ) );
				return false;
			}
			else{
				if( version_compare(MAILPOET_WOOVERSION, MAILPOET_WOOCOMMERCE_WOO_VERSION_REQUIRE, '<' ) ) {
					add_action('admin_notices', array( &$this, 'display_req_woo_notice' ) );
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Display the WordPress requirement notice.
	 *
	 * @access static
	 */
	static function display_req_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WordPress ' . MAILPOET_WOOCOMMERCE_WP_VERSION_REQUIRE . ' or higher. Please upgrade your WordPress setup', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN), MAILPOET_WOOCOMMERCE );
		echo '</p></div>';
	}

	/**
	 * Display the WooCommerce requirement notice.
	 *
	 * @access static
	 */
	static function display_req_woo_not_active_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WooCommerce to be installed and activated first. Please <a href="%s">install WooCommerce</a> first.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN), MAILPOET_WOOCOMMERCE, admin_url('plugin-install.php?tab=search&type=term&s=WooCommerce') );
		echo '</p></div>';
	}

	/**
	 * Display the WooCommerce requirement notice.
	 *
	 * @access static
	 */
	static function display_req_woo_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WooCommerce ' . MAILPOET_WOOCOMMERCE_WOO_VERSION_REQUIRE . ' or higher. Please update WooCommerce for %s to work.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN), MAILPOET_WOOCOMMERCE, MAILPOET_WOOCOMMERCE );
		echo '</p></div>';
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {
		include_once( 'includes/mailpoet-woocommerce-core-functions.php' ); // Contains core functions for the front/back end.

		if ( is_admin() ) {
			$this->admin_includes();
		}
		else{
			include_once( 'includes/mailpoet-woocommerce-hooks.php' ); // Hooks used at the frontend.
		}

	}

	/**
	 * Include required admin files.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		// Load WooCommerce class if they exist.
		if( version_compare(MAILPOET_WOOVERSION, '2.0.20', '>' ) ) {
			// Include the settings page to add our own settings.
			include_once( $this->wc_plugin_path() . 'includes/admin/settings/class-wc-settings-page.php' );
			$this->wc_settings_page = new WC_Settings_Page(); // Call the settings page for WooCommerce.
		}

		include_once( 'includes/admin/class-mailpoet-woocommerce-install.php' ); // Install plugin
		include_once( 'includes/admin/class-mailpoet-woocommerce-admin.php' ); // Admin section
	}
	/**
	 * Runs when the plugin is initialized.
	 *
	 * @access public
	 */
	public function init_mailpoet_woocommerce_add_on() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any 
	 * following ones if the same translation is present.
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), self::text_domain );

		load_textdomain( self::text_domain, WP_LANG_DIR . "/".self::slug."/" . $locale . ".mo" );

		// Set Plugin Languages Directory
		// Plugin translations can be filed in the mailpoet_woocommerce/languages/ directory
		// Wordpress translations can be filed in the wp-content/languages/ directory
		load_plugin_textdomain( self::text_domain, false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the plugin path for WooCommerce.
	 *
	 * @access public
	 * @return string
	 */
	public function wc_plugin_path() {
		return untrailingslashit( plugin_dir_path( plugin_dir_path( __FILE__ ) ) ) . '/woocommerce/';
	}

} // end class

/**
 * Returns the main instance of MailPoet_WooCommerce_Add_on to prevent the need to use globals.
 *
 * @return MailPoet WooCommerce Add-on
 */
function MailPoet_WooCommerce_Add_on() {
	return MailPoet_WooCommerce_Add_on::instance();
}

// Global for backwards compatibility.
$GLOBALS['mailpoet_woocommerce_add_on'] = MailPoet_WooCommerce_Add_on();

?>