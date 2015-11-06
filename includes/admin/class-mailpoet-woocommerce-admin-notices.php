<?php
/**
 * Display notices in the WordPress admin.
 *
 * @since    3.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @license  GPL-2.0+
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

if(! class_exists('MailPoet_WooCommerce_Add_On_Admin_Notices')){

	/**
	 * Class - MailPoet_WooCommerce_Add_On_Admin_Notices
	 *
	 * @since 3.0.0
	 */
	class MailPoet_WooCommerce_Add_On_Admin_Notices {

		/**
		 * Constructor
		 *
		 * @since  3.0.0
		 * @access public
		 */
		public function __construct() {
			add_action('admin_init', array($this, 'check_wp'));
			add_action('admin_init', array($this, 'add_notices'));
		} // END __construct()

		/**
		* Checks if MailPoet and WooCommerce is installed and
		* meets the required minimum version.
		*
		* @since  3.0.0
		* @access public
		*/
		public function add_notices() {
			if( !in_array('wysija-newsletters/index.php', apply_filters('active_plugins', get_option('active_plugins'))) ){
				add_action('admin_notices', array($this, 'requirement_mailpoet_notice'), 10);
				return false;
			}

			if( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ){
				if( version_compare(WC_VERSION, MAILPOET_WOOCOMMERCE_WC_VERSION_REQUIRED, '<') ){
					add_action('admin_notices', array($this, 'requirement_wc_notice'), 10);
					return false;
				}
			}

			return true;
		} // END add_notices()

		/**
		 * Checks that the WordPress version meets the plugin requirement.
		 *
		 * @since  3.0.0
		 * @access public
		 * @global string $wp_version
		 * @return bool
		 */
		public function check_wp() {
			global $wp_version;

			if(! version_compare($wp_version, MAILPOET_WOOCOMMERCE_WP_VERSION_REQUIRED, '>=') ){
				add_action('admin_notices', array($this, 'requirement_wp_notice'), 10);
				return false;
			}

			return true;
		} // END check_requirements()

		/**
		 * Show the WordPress requirement notice.
		 *
		 * @since  3.0.0
		 * @access public
		 */
		public function requirement_wp_notice() {
			include('views/html-notice-requirement-wp.php');
		} // END requirement_wp_notice()

		/**
		 * Show the WooCommerce requirement notice.
		 *
		 * @since  3.0.0
		 * @access public
		 */
		public function requirement_wc_notice() {
			include('views/html-notice-requirement-wc.php');
		} // END requirement_wc_notice()

		/**
		 * Show the MailPoet requirement notice.
		 *
		 * @since  3.0.0
		 * @access public
		 */
		public function requirement_mailpoet_notice() {
			include('views/html-notice-requirement-mailpoet.php');
		} // END requirement_mailpoet_notice()

	} // END MailPoet_WooCommerce_Add_On_Admin_Notices class.

} // END if class exists.

return new MailPoet_WooCommerce_Add_On_Admin_Notices();
