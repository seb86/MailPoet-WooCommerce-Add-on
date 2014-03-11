<?php
/**
 * MailPoet WooCommerce Add-on Settings
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet WooCommerce Add-on/Admin
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_WooCommerce_Settings' ) ) {

/**
 * MailPoet_WooCommerce_Settings
 */
class MailPoet_WooCommerce_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id 		= MAILPOET_WOOCOMMERCE_PAGE;
		$this->label 	= __( 'MailPoet', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN );

		add_filter( 'woocommerce_settings_submenu_array', array( &$this, 'add_menu_page' ), 30 );
		add_filter( 'woocommerce_settings_tabs_array', array( &$this, 'add_settings_page' ), 30 );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'woocommerce_settings_' . $this->id, array( &$this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( &$this, 'save' ) );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = apply_filters('woocommerce_mailpoet_settings_sections', array(
			'' => __( 'General', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN ),
			'lists' => __('Lists', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
		) );

		return $sections;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		global $current_section;

		if( $current_section != 'lists' ){

			return apply_filters($this->id . '_general_settings', array(

				array(
					'name' 	=> __('MailPoet Newsletter', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
					'type' 	=> 'title',
					'desc' 	=> __('Now your customers can subscribe to newsletters you have created with MailPoet. Simply setup your settings below and press "Save Changes".', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
					'id' 	=> $this->id . '_general_options'
				),

				array(
					'name' 			=> __('Enable subscribe on checkout', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
					'desc' 			=> __('Add a subscribe checkbox to your checkout page.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
					'id' 			=> 'mailpoet_woocommerce_enable_checkout',
					'type' 			=> 'checkbox',
					'default' 		=> '1',
				),

				array(
					'name' 			=> __('Checkbox label', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
					'desc' 			=> __('Enter a message to place next to the checkbox.', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
					'desc_tip' 		=> true,
					'id' 			=> 'mailpoet_woocommerce_checkout_label',
					'type' 			=> 'text',
					'placeholder' 	=> __('Yes, add me to your mailing list', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN),
				),

				array(
					'type' => 'sectionend', 
					'id' => $this->id . '_general_options'
				),

			)); // End general settings.
		}
		else{

			return apply_filters($this->id . '_lists_settings', array(

				array(
					'title' 	=> __('Lists', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN), 
					'type' 		=> 'title', 
					'desc' 		=> __('Here lists all the campaigns you can assign the customers to when they subscribe. Simply tick the lists you want your customers to subscribe to and press "Save Changes".', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN), 
					'id' 		=> $this->id . '_lists_options'
				),

				array(
					'type' 		=> 'sectionend', 
					'id' 		=> $this->id . '_lists_options'
				),

			)); // End lists settings
		}
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings();

		WC_Admin_Settings::output_fields( $settings );

		if($current_section == 'lists'){
			include_once(MailPoet_WooCommerce_Add_on()->plugin_path() . '/includes/admin/settings/settings-newsletters.php');

			$mailpoet_list = mailpoet_lists();

			do_action('woocommerce_mailpoet_list_newsletters', $mailpoet_list);
		}
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );

		if($current_section == 'lists'){
			// Each list that has been ticked will be saved.
			if(isset($_POST['checkout_lists'])){
				$checkout_lists = $_POST['checkout_lists'];
				update_option('mailpoet_woocommerce_subscribe_too', $checkout_lists);
			}
			else{
				delete_option('mailpoet_woocommerce_subscribe_too');
			}
		}
	}

}

} // end if class exists

return new MailPoet_WooCommerce_Settings();

?>