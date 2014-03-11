<?php
/**
 * Add some content to the help tab.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet WooCommerce Add-on/Admin
 * @version 	1.0.0
 * @since 		2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_WooCommerce_Admin_Help' ) ) {

/**
 * MailPoet_WooCommerce_Admin_Help Class
 */
class MailPoet_WooCommerce_Admin_Help {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'current_screen', array( &$this, 'add_tabs' ), 50 );
	}

	/**
	 * Add help tabs
	 */
	public function add_tabs() {
		$screen = get_current_screen();

		if ( ! in_array( $screen->id, mailpoet_woocommerce_get_screen_ids() ) )
			return;

		if( version_compare( MAILPOET_WOOVERSION, "2.1.0", '<' ) ) {
			$screen->add_help_tab( array(
				'id'	=> 'mailpoet_woocommerce_docs_tab',
				'title'	=> __( 'Documentation', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ),
				'content'	=>

					'<p>' . sprintf( __( 'Thank you for using %s :) Should you need help using %s please read the documentation.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name, MailPoet_WooCommerce_Add_on()->name ) . '</p>' .

					'<p><a href="' . MailPoet_WooCommerce_Add_on()->doc_url . '" class="button button-primary">' . sprintf( __( '%s Documentation', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name ) . '</a></p>'

			) );

			$screen->add_help_tab( array(
				'id'	=> 'mailpoet_woocommerce_support_tab',
				'title'	=> __( 'Support', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ),
				'content'	=>

					'<p>' . sprintf( __( 'After <a href="%s">reading the documentation</a>, for further assistance you can use the <a href="%s">community forum</a>.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->doc_url, MailPoet_WooCommerce_Add_on()->wp_plugin_support_url ) . '</p>' .

					'<p>' . __( 'Before asking for help I recommend checking the status page to identify any problems with your configuration.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</p>' .

					'<p><a href="' . admin_url('admin.php?page=woocommerce_status') . '" class="button button-primary">' . __( 'System Status', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a> <a href="' . MailPoet_WooCommerce_Add_on()->wp_plugin_support_url . '" class="button">' . __( 'Community Support', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a></p>'

			) );

			$screen->add_help_tab( array(
				'id'	=> 'mailpoet_woocommerce_bugs_tab',
				'title'	=> __( 'Found a bug?', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ),
				'content'	=>

					'<p>' . sprintf( __( 'If you find a bug within <strong>%s</strong> you can create a ticket via <a href="%s">Github issues</a>. Ensure you read the <a href="%s">contribution guide</a> prior to submitting your report. Be as descriptive as possible and please include your <a href="%s">system status report</a>.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name, GITHUB_REPO_URL . 'issues?state=open', GITHUB_REPO_URL . 'blob/master/CONTRIBUTING.md', admin_url( 'admin.php?page=woocommerce_status' ) ) . '</p>' .

					'<p><a href="' . GITHUB_REPO_URL . 'issues?state=open" class="button button-primary">' . __( 'Report a bug', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a> <a href="' . admin_url('admin.php?page=woocommerce_status') . '" class="button">' . __( 'System Status', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a></p>'

			) );

			$screen->set_help_sidebar(
				'<p><strong>' . __( 'For more information:', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</strong></p>' .
				'<p><a href=" ' . MailPoet_WooCommerce_Add_on()->web_url . ' " target="_blank">' . sprintf( __( 'MailPoet', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name ) . '</a></p>' .
				'<p><a href=" ' . MailPoet_WooCommerce_Add_on()->wp_plugin_url . ' " target="_blank">' . __( 'Project on WordPress.org', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a></p>' .
				'<p><a href="' . GITHUB_REPO_URL . '" target="_blank">' . __( 'Project on Github', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a></p>'
			);
		}
		else{
			$screen->add_help_tab( array(
				'id'	=> 'mailpoet_woocommerce_tab',
				'title'	=> MailPoet_WooCommerce_Add_on()->name,
				'content'	=>

					'<p>' . sprintf( __( 'Thank you for using %s :) Should you need help using %s please read the documentation.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name, MailPoet_WooCommerce_Add_on()->name ) . '</p>' .

					'<p><a href="' . MailPoet_WooCommerce_Add_on()->doc_url . '" class="button button-primary">' . sprintf( __( '%s Documentation', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name ) . '</a></p><br>'.

					'<strong>' . __( 'Support', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</strong>'.
					'<p>' . sprintf( __( 'After <a href="%s">reading the documentation</a>, for further assistance you can use the <a href="%s">community forum</a>.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->doc_url, MailPoet_WooCommerce_Add_on()->wp_plugin_support_url ) . '</p>' .

					'<p>' . __( 'Before asking for help I recommend checking the status page to identify any problems with your configuration.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</p>' .

					'<strong>' . __( 'Found a bug?', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</strong>'.
					'<p>' . sprintf( __( 'If you find a bug within <strong>%s</strong> you can create a ticket via <a href="%s">Github issues</a>. Ensure you read the <a href="%s">contribution guide</a> prior to submitting your report. Be as descriptive as possible and please include your <a href="%s">system status report</a>.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ), MailPoet_WooCommerce_Add_on()->name, GITHUB_REPO_URL . 'issues?state=open', GITHUB_REPO_URL . 'blob/master/CONTRIBUTING.md', admin_url( 'admin.php?page=wc-status' ) ) . '</p>' .

					'<p><a href="' . admin_url('admin.php?page=wc-status') . '" class="button button-primary">' . __( 'System Status', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a> <a href="' . MailPoet_WooCommerce_Add_on()->wp_plugin_support_url . '" class="button">' . __( 'Community Support', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a> <a href="' . GITHUB_REPO_URL . 'issues?state=open" class="button">' . __( 'Report a bug', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '</a></p>'

			) );

		}
	}

} // end class.

} // end if class exists.

return new MailPoet_WooCommerce_Admin_Help();

?>