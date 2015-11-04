<?php
/**
 * MailPoet WooCommerce Add-on Admin.
 *
 * @since    1.0.0
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @license  GPL-2.0+
 * @version  3.0.0
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

if(! class_exists('MailPoet_WooCommerce_Add_On_Admin')){

	class MailPoet_WooCommerce_Add_On_Admin {

		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			add_action('init', array($this, 'includes'), 10);
			add_filter('plugin_action_links_'.plugin_basename(MAILPOET_WOOCOMMERCE_FILE), array($this, 'action_links'));
			add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
		}

		/**
		 * Include any classes we need within admin.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function includes() {
			include('class-mailpoet-woocommerce-admin-notices.php');
			include('class-mailpoet-woocommerce-admin-settings.php');
		} // END includes()

		/**
		 * Plugin action links.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  mixed $links
		 * @return void
		 */
		public function action_links( $links ) {
			if( current_user_can('manage_woocommerce') ){
				$plugin_links = array(
					'<a href="'.admin_url( 'admin.php?page=wc-settings&tab='.MAILPOET_WOOCOMMERCE_SLUG ).'">'.__('Settings', 'mailpoet-woocommerce-add-on').'</a>',
				);

				return array_merge( $plugin_links, $links );
			}

			return $links;
		} // END action_links()

		/**
		 * Plugin row meta links
		 *
		 * @since  3.0.0
		 * @access public
		 * @param  array  $input already defined meta links
		 * @param  string $file  plugin file path and name being processed
		 * @return array  $input
		 */
		public function plugin_row_meta( $input, $file ) {
			if( plugin_basename(MAILPOET_WOOCOMMERCE_FILE) !== $file) {
				return $input;
			}

			$links = array(
				'<a href="'.esc_url('https://github.com/seb86/MailPoet-WooCommerce-Add-on/wiki/').'" target="_blank">'.__('Documentation', 'mailpoet-woocommerce-add-on').'</a>',
				'<a href="'.esc_url('https://wordpress.org/support/plugin/mailpoet-woocommerce-add-on/').'" target="_blank">'.__('Community Support', 'mailpoet-woocommerce-add-on').'</a>'
			);

			$input = array_merge( $input, $links );

			return $input;
		} // END plugin_row_meta()

	} // END class

} // END if class exists

return new MailPoet_WooCommerce_Add_On_Admin();
