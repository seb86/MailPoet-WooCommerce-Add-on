<?php
/**
 * Installation related functions and actions.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet WooCommerce Add-on/Classes
 * @version 	2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_WooCommerce_Install' ) ) {

/**
 * MailPoet_WooCommerce_Install Class
 */
class MailPoet_WooCommerce_Install {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		register_activation_hook( MAILPOET_WOOCOMMERCE, array( &$this, 'install' ) );

		add_action( 'admin_init', array( &$this, 'install' ), 5 );
		add_action( 'in_plugin_update_message-'.plugin_basename( MAILPOET_WOOCOMMERCE_FILE ), array( &$this, 'in_plugin_update_message' ) );
	}

	/**
	 * Install MailPoet WooCommerce Add-on
	 */
	public function install() {
		$this->create_options();
	}

	/**
	 * Default options
	 *
	 * Sets up the default options used on the settings page
	 *
	 * @access public
	 */
	function create_options() {
		/** 
		 * This loads the settings for the plugin.
		 * First checks what version of WooCommerce is active,
		 * then loads the appropriate format.
		 */
		if( version_compare(MAILPOET_WOOVERSION, "2.0.20", '<=') ) {
			include_once( 'settings/v2.0.20/class-mailpoet-woocommerce-admin-settings.php' );

			$this->settings = new MailPoet_WooCommerce_Add_on_Admin_Settings();
			$this->settings->get_settings();

			// Run through each settings to load the default settings.
			foreach ( $this->settings as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}
		else{
			// Include settings so that we can run through defaults.
			include_once( 'class-mailpoet-woocommerce-admin-settings.php' );

			$settings = MailPoet_WooCommerce_Admin_Settings::get_settings_pages();

			// Run through each section and settings to load the default settings.
			foreach ( $settings as $section ) {
				$section = $section->get_settings();
				foreach ( $section as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
		}
	}

	/**
	 * Show details of plugin changes on Installed Plugin Screen.
	 *
	 * @return void
	 */
	function in_plugin_update_message() {
		$response = wp_remote_get( MAILPOET_WOOCOMMERCE_README_FILE );

		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {

			// Output Upgrade Notice
			$matches = null;
			$regexp = '~==\s*Upgrade Notice\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote( MAILPOET_WOOCOMMERCE_VERSION ) . '\s*=|$)~Uis';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$notices = (array) preg_split('~[\r\n]+~', trim( $matches[1] ) );

				echo '<div style="font-weight: normal; background: #CD1049; color: #fff !important; border: 1px solid rgba(205,16,73,0.25); padding: 8px; margin: 9px 0;">';

				foreach ( $notices as $index => $line ) {
					echo '<p style="margin: 0; font-size: 1.1em; color: #fff; text-shadow: 0 1px 1px #6E1644;">' . preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line ) . '</p>';
				}

				echo '</div>';
			}

			// Output Changelog
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*-(.*)=(.*)(=\s*' . preg_quote( MAILPOET_WOOCOMMERCE_VERSION ) . '\s*-(.*)=|$)~Uis';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$changelog = (array) preg_split('~[\r\n]+~', trim( $matches[2] ) );

				echo ' ' . __( 'What\'s new:', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ) . '<div style="font-weight: normal;">';

				$ul = false;

				foreach ( $changelog as $index => $line ) {
					if ( preg_match('~^\s*\*\s*~', $line ) ) {
						if ( ! $ul ) {
							echo '<ul style="list-style: disc inside; margin: 9px 0 9px 20px; overflow:hidden; zoom: 1;">';
							$ul = true;
						}
						$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
						echo '<li style="width: 50%; margin: 0; float: left; ' . ( $index % 2 == 0 ? 'clear: left;' : '' ) . '">' . $line . '</li>';
					}
					else {
						if ( $ul ) {
							echo '</ul>';
							$ul = false;
						}
						echo '<p style="margin: 9px 0;">' . htmlspecialchars( $line ) . '</p>';
					}
				}

				if ($ul) {
					echo '</ul>';
				}

				echo '</div>';
			}
		}
	}
}

} // end if class exists.

return new MailPoet_WooCommerce_Install();

?>