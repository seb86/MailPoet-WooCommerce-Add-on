<?php
/**
 * MailPoet WooCommerce Add-on Admin.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet WooCommerce Add-on/Admin
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_WooCommerce_Admin' ) ) {

class MailPoet_WooCommerce_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( &$this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		// Functions
		include( 'mailpoet-woocommerce-admin-functions.php' );

		// Classes we only need if the ajax is not-ajax
		if ( ! is_ajax() ) {
			// Help
			if ( apply_filters( 'mailpoet_woocommerce_enable_admin_help_tab', true ) ) {
				include( 'class-mailpoet-woocommerce-admin-help.php' );
			}
		}
	}

}

} // end if class exists

return new MailPoet_WooCommerce_Admin();

?>