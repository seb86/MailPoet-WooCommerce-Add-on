<?php
/**
 * MailPoet WooCommerce Add-on Settings
 *
 * @class    MailPoet_WooCommerce_Add_On_Settings
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @license  GPL-2.0+
 * @since    1.0.0
 * @version  4.0.0
 */
if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'MailPoet_WooCommerce_Add_On_Settings' ) ) {

	class MailPoet_WooCommerce_Add_On_Settings {

		/**
		 * Constructor.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 4.0.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_settings_submenu_array', array( $this, 'add_menu_page' ), 30 );
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_mailpoet_settings_tab' ), 30 );
			add_action( 'woocommerce_sections_mailpoet-woocommerce-add-on', array( $this, 'output_sections' ) );
			add_action( 'woocommerce_settings_mailpoet-woocommerce-add-on', array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_mailpoet-woocommerce-add-on', array( $this, 'save' ) );
		} // END constuct()

		/**
		 * Add MailPoet settings tab to the WooCommerce settings tabs array.
		 *
		 * @access  public
		 * @since   3.0.0
		 * @version 4.0.0
		 * @param   array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the MailPoet tab.
		 * @return  array $settings_tabs Array of WooCommerce setting tabs & their labels, including the MailPoet tab.
		 */
		public static function add_mailpoet_settings_tab( $settings_tabs ) {
			$settings_tabs[ 'mailpoet-woocommerce-add-on' ] = 'MailPoet';

			return $settings_tabs;
		} // END add_mailpoet_settings_tab()

		/**
		 * Get each section to seperate the MailPoet settings.
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 * @return array
		 */
		public static function get_sections() {
			$sections = array(
				''      => __( 'General', 'mailpoet-woocommerce-add-on' ),
				'lists' => __( 'Available Lists', 'mailpoet-woocommerce-add-on' ),
			);

			return $sections;
		} // END get_sections()

		/**
		 * Gets the settings for the add-on.
		 *
		 * @access  public
		 * @static
		 * @since   1.0.0
		 * @version 4.0.0
		 * @param   string $current_section
		 * @filter  mailpoet_woocommerce_subscription_position
		 * @return  array
		 */
		public static function get_settings( $current_section = '' ) {
			if ( empty( $current_section ) || $current_section != 'lists' ) {

				return array(

					array(
						'name' => 'MailPoet WooCommerce Add-on',
						'type' => 'title',
						'desc' => __( 'Now your customers can subscribe to newsletters you have created with MailPoet as they order. These settings control how your customers subscribe.', 'mailpoet-woocommerce-add-on' ),
						'id'   => 'mailpoet-woocommerce-add-on_general_options'
					),

					array(
						'name'     => __( 'Enable subscription?', 'mailpoet-woocommerce-add-on' ),
						'desc'     => __( 'Tick this box to enable MailPoet subscription during checkout.', 'mailpoet-woocommerce-add-on' ),
						'id'       => 'mailpoet_woocommerce_enable_checkout',
						'type'     => 'checkbox',
						'default'  => '1',
					),

					array(
						'title'    => __( 'Multi-Subscription?', 'mailpoet-woocommerce-add-on' ),
						'desc'     => sprintf( __( 'Have more than one newsletter?<br /><a class="button button-primary" href="%s" target="_blank">Edit Lists</a> <a class="button" href="%s">Available Lists</a>', 'mailpoet-woocommerce-add-on' ), admin_url( 'admin.php?page=wysija_subscribers&action=lists' ), admin_url( 'admin.php?page=wc-settings&tab=mailpoet-woocommerce-add-on&section=lists' ) ),
						'desc_tip' => false,
						'id'       => 'mailpoet_woocommerce_customer_selects',
						'class'    => 'wc-enhanced-select',
						'css'      => 'min-width:100px;',
						'default'  => 'no',
						'type'     => 'select',
						'options'  => array(
							'no'  => __( 'No', 'mailpoet-woocommerce-add-on' ),
							'yes' => __( 'Yes', 'mailpoet-woocommerce-add-on' ),
						),
					),

					array(
						'name'     => __( 'Enable Double Opt-in?', 'mailpoet-woocommerce-add-on' ),
						'desc'     => __( 'Controls whether a double opt-in confirmation message is sent, defaults to true.', 'mailpoet-woocommerce-add-on' ),
						'id'       => 'mailpoet_woocommerce_double_optin',
						'type'     => 'checkbox',
						'default'  => 'yes',
					),

					array(
						'name'     => __( 'Default checkbox status', 'mailpoet-woocommerce-add-on' ),
						'desc'     => __( 'The default state of the subscribe checkbox. Be aware some countries have laws against using opt-out checkboxes.', 'mailpoet-woocommerce-add-on' ),
						'desc_tip' => true,
						'id'       => 'mailpoet_woocommerce_checkbox_status',
						'class'    => 'wc-enhanced-select',
						'css'      => 'min-width:100px;',
						'default'  => 'unchecked',
						'type'     => 'select',
						'options'  => array(
							'checked'   => __( 'Checked', 'mailpoet-woocommerce-add-on' ),
							'unchecked' => __( 'Un-checked', 'mailpoet-woocommerce-add-on' )
						),
					),

					array(
						'name'        => __( 'Subscribe checkbox label', 'mailpoet-woocommerce-add-on' ),
						'desc'        => __( 'The label you want to display next to the "Subscribe to Newsletter" checkbox.', 'mailpoet-woocommerce-add-on' ),
						'desc_tip'    => true,
						'id'          => 'mailpoet_woocommerce_checkout_label',
						'css'         => 'min-width:350px;',
						'type'        => 'text',
						'placeholder' => __( 'Yes, please subscribe me to the newsletter.', 'mailpoet-woocommerce-add-on' ),
					),

					array(
						'name'     => __( 'Subscription Position', 'mailpoet-woocommerce-add-on' ),
						'desc'     => __( 'Select where on the checkout page you want to display the subscription sign-up.', 'mailpoet-woocommerce-add-on' ),
						'desc_tip' => true,
						'id'       => 'mailpoet_woocommerce_subscription_position',
						'default'  => 'after_order_notes',
						'class'    => 'wc-enhanced-select',
						'css'      => 'min-width:300px;',
						'type'     => 'select',
						'options'  => apply_filters( 'mailpoet_woocommerce_subscription_position', array(
							'before_checkout_billing_form'  => __( 'Before Billing Form', 'mailpoet-woocommerce-add-on' ),
							'after_checkout_billing_form'   => __( 'After Billing Form', 'mailpoet-woocommerce-add-on' ),
							'before_checkout_shipping_form' => __( 'Before Shipping Form', 'mailpoet-woocommerce-add-on' ),
							'after_checkout_shipping_form'  => __( 'After Shipping Form', 'mailpoet-woocommerce-add-on' ),
							'before_order_notes'            => __( 'Before Order Notes', 'mailpoet-woocommerce-add-on' ),
							'after_order_notes'             => __( 'After Order Notes', 'mailpoet-woocommerce-add-on' ),
							'review_order_after_submit'     => __( 'After Order Submit', 'mailpoet-woocommerce-add-on' )
						)),
					),

					array(
						'title'   => __( 'Remove all data on uninstall?', 'mailpoet-woocommerce-add-on' ),
						'desc'    => __( 'If enabled, all settings for this plugin will all be deleted when uninstalling via Plugins > Delete.', 'mailpoet-woocommerce-add-on' ),
						'id'      => 'mailpoet_woocommerce_uninstall_data',
						'default' => 'no',
						'type'    => 'checkbox'
					),

					array(
						'type' => 'sectionend',
						'id'   => 'mailpoet-woocommerce-add-on_general_options'
					),

				); // End general settings.
			}
			else {

				return array(

					array(
						'title' => __( 'Available Lists', 'mailpoet-woocommerce-add-on' ),
						'type'  => 'title',
						'desc'  => __( 'Simply tick the lists you want your customers to subscribe to or allow the customer to choose from and press "Save changes".', 'mailpoet-woocommerce-add-on' ),
						'id'    => 'mailpoet-woocommerce-add-on_lists_options'
					),

					array(
						'type' => 'sectionend',
						'id'   => 'mailpoet-woocommerce-add-on_lists_options'
					),

				); // End lists settings
			}
		} // get_settings()

		/**
		 * Creates sections beneath the MailPoet tab.
		 *
		 * @access  public
		 * @since   3.0.0
		 * @version 4.0.0
		 * @global  $current_section
		 * @uses    self::get_sections()
		 * @uses    admin_url()
		 */
		public function output_sections() {
			global $current_section;

			$sections = self::get_sections();

			if ( empty( $sections ) ) {
				return;
			}

			echo '<ul class="subsubsub">';

			$array_keys = array_keys( $sections );

			foreach ( $sections as $id => $label ) {
				echo '<li><a href=" ' . admin_url( 'admin.php?page=wc-settings&tab=mailpoet-woocommerce-add-on&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
			}

			echo '</ul><br class="clear" />';
		} // END output_sections()

		/**
		 * Output the settings
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 4.0.0
		 * @global  $current_section
		 * @uses    woocommerce_admin_fields()
		 * @uses    self::get_settings()
		 * @uses    wp_enqueue_script()
		 */
		public function output() {
			global $current_section;

			$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'mailpoet_woocommerce_settings',  MailPoet_WooCommerce_Add_on::plugin_url() . '/assets/js/admin/settings' . $suffix . '.js', array( 'jquery', 'selectWoo' ), MailPoet_WooCommerce_Add_On::$version, true );

			woocommerce_admin_fields( self::get_settings( $current_section ) );

			if ( $current_section == 'lists' ){
				include_once( MailPoet_WooCommerce_Add_On::plugin_path() . '/includes/admin/settings/mailpoet-lists.php' );

				$mailpoet_list = mailpoet_lists();

				do_action( 'woocommerce_mailpoet_list_newsletters', $mailpoet_list );
			}

			wp_nonce_field( 'mailpoet_wc_settings', '_mailpoet_wc_nonce', false );
		} // END output()

		/**
		 * Save settings
		 *
		 * @access  public
		 * @since   1.0.0
		 * @version 4.0.0
		 * @global  $current_section
		 * @uses    woocommerce_update_options()
		 * @uses    self::get_settings()
		 */
		public function save() {
			global $current_section;

			if ( empty( $_POST['_mailpoet_wc_nonce'] ) || ! wp_verify_nonce( $_POST['_mailpoet_wc_nonce'], 'mailpoet_wc_settings' ) ) {
				return;
			}

			if ( $current_section == 'lists' ) {
				// Each list that has been ticked will be saved.
				if ( isset( $_POST['checkout_lists'] ) ) {
					$checkout_lists = $_POST['checkout_lists'];
					update_option( 'mailpoet_woocommerce_subscribe_too', $checkout_lists );
				}
				else {
					delete_option( 'mailpoet_woocommerce_subscribe_too' );
				}
			}
			else {
				woocommerce_update_options( self::get_settings() );
			}
		} // END save()

	} // END class

} // END if class exists

return new MailPoet_WooCommerce_Add_On_Settings();
