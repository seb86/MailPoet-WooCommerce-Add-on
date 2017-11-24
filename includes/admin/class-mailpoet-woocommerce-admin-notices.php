<?php
/**
 * Display notices in the WordPress admin.
 *
 * @class    MailPoet_WooCommerce_Add_On_Admin_Notices
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @license  GPL-2.0+
 * @since    3.0.0
 * @version  4.0.0
 */
if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'MailPoet_WooCommerce_Add_On_Admin_Notices' ) ) {

	class MailPoet_WooCommerce_Add_On_Admin_Notices {

		/**
		 * Constructor
		 *
		 * @since  3.0.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'add_notices' ) );
		} // END __construct()

		/**
		 * Checks if MailPoet 2 is installed.
		 *
		 * @access  public
		 * @since   3.0.0
		 * @version 4.0.0
		 */
		public function add_notices() {
			if ( ! in_array( 'wysija-newsletters/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_action( 'admin_notices', array( $this, 'requirement_mailpoet_notice' ), 10 );
				return false;
			}

			return true;
		} // END add_notices()

		/**
		 * Show the MailPoet requirement notice.
		 *
		 * @access  public
		 * @since   3.0.0
		 * @version 4.0.0
		 */
		public function requirement_mailpoet_notice() {
			include( MailPoet_WooCommerce_Add_On::plugin_path() . '/includes/admin/views/html-notice-requirement-mailpoet.php' );
		} // END requirement_mailpoet_notice()

	} // END MailPoet_WooCommerce_Add_On_Admin_Notices class.

} // END if class exists.

return new MailPoet_WooCommerce_Add_On_Admin_Notices();
