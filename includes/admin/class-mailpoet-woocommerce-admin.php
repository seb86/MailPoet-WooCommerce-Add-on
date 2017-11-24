<?php
/**
 * MailPoet WooCommerce Add-on Admin.
 *
 * @class    MailPoet_WooCommerce_Add_On_Admin
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

if ( ! class_exists( 'MailPoet_WooCommerce_Add_On_Admin' ) ) {

	class MailPoet_WooCommerce_Add_On_Admin {

		/**
		 * Load the plugin.
		 *
		 * @access public
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'includes' ), 10 );
			add_filter( 'plugin_action_links_' . MailPoet_WooCommerce_Add_On::plugin_basename(), array( $this, 'action_links' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta_links' ), 10, 3 );
		}

		/**
		 * Include any classes we need within admin.
		 *
		 * @since   1.0.0
		 * @version 4.0.0
		 * @access  public
		 */
		public function includes() {
			include( MailPoet_WooCommerce_Add_On::plugin_path() . '/includes/admin/class-mailpoet-woocommerce-admin-settings.php' );
		} // END includes()

		/**
		 * Plugin action links.
		 *
		 * @since   1.0.0
		 * @version 4.0.0
		 * @access  public
		 * @param   mixed $links
		 * @return  void
		 */
		public function action_links( $links ) {
			if ( current_user_can( 'manage_woocommerce' ) ){
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=mailpoet-woocommerce-add-on').'">' . __( 'Settings', 'mailpoet-woocommerce-add-on' ) . '</a>'
				);

				return array_merge( $plugin_links, $links );
			}

			return $links;
		} // END action_links()

		/**
		 * Show row meta on the plugin screen.
		 *
		 * @access public
		 * @since  4.0.0
		 * @param  mixed $links Plugin Row Meta
		 * @param  mixed $file  Plugin Base file
		 * @return array
		 */
		public function plugin_meta_links( $links, $file, $data ) {
			if ( $file == MailPoet_WooCommerce_Add_On::plugin_basename() ) {
				$links[ 1 ] = sprintf( __( 'Developed By %s', 'mailpoet-woocommerce-add-on' ), '<a href="' . $data[ 'AuthorURI' ] . '" target="_blank">' . $data[ 'Author' ] . '</a>' );

				$links[ 2 ] = '<a href="' . esc_url( 'https://github.com/seb86/MailPoet-WooCommerce-Add-on/wiki/') . '" target="_blank">' . __( 'Documentation', 'mailpoet-woocommerce-add-on' ) . '</a>';
			}

			return $links;
		} // END plugin_meta_links()

	} // END class

} // END if class exists

return new MailPoet_WooCommerce_Add_On_Admin();
