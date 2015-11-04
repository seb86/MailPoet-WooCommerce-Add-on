<?php
/**
 * MailPoet WooCommerce Add-on Settings
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @version  3.0.0
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_WooCommerce_Add_On_Settings' ) ) {

	/**
	 * MailPoet_WooCommerce_Settings
	 */
	class MailPoet_WooCommerce_Add_On_Settings extends WC_Settings_Page {

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			$this->id    = MAILPOET_WOOCOMMERCE_PAGE;
			$this->label = 'MailPoet';

			add_filter('woocommerce_settings_submenu_array', array($this, 'add_menu_page'), 30);
			add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 30);
			//add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections') );
			add_action('woocommerce_settings_' . $this->id, array($this, 'output') );
			add_action('woocommerce_settings_save_' . $this->id, array($this, 'save') );
		}

		/**
		 * Get sections
		 *
		 * @since  1.0.0
		 * @access public
		 * @return array
		 */
		public function get_sections() {
			$sections = array(
				''      => __('General', 'mailpoet-woocommerce-add-on'),
				'lists' => __('Lists', 'mailpoet-woocommerce-add-on'),
			);

			return $sections;
		}

		/**
		 * Get settings array
		 *
		 * @since  1.0.0
		 * @access public
		 * @global $current_section
		 * @return array
		 */
		public function get_settings() {
			global $current_section;

			if( $current_section != 'lists' ){

				return array(

					array(
						'name' => __('MailPoet Newsletter', 'mailpoet-woocommerce-add-on'),
						'type' => 'title',
						'desc' => __('Now your customers can subscribe to newsletters you have created with MailPoet.', 'mailpoet-woocommerce-add-on'),
						'id'   => $this->id . '_general_options'
					),

					array(
						'name'    => __('Enable subscription?', 'mailpoet-woocommerce-add-on'),
						'desc'    => __('Add a checkbox on your checkout page for your customers to subscribe to your newsletters.', 'mailpoet-woocommerce-add-on'),
						'id'      => 'mailpoet_woocommerce_enable_checkout',
						'type'    => 'checkbox',
						'default' => '1',
					),

					array(
						'title'    => __('Customer Selects?', 'mailpoet-woocommerce-add-on'),
						'desc'     => __('Have more than one newsletter? Allow your customers to select which list to subscribe to.', 'mailpoet-woocommerce-add-on'),
						'desc_tip' => false,
						'id'       => 'mailpoet_woocommerce_customer_selects',
						'class'    => 'chosen-select',
						'css'      => 'min-width:300px;',
						'default'  => 'no',
						'type'     => 'select',
						'options'  => array(
							'no'  => __('No', 'mailpoet-woocommerce-add-on'),
							'yes' => __('Yes', 'mailpoet-woocommerce-add-on'),
						),
						'autoload' => false
					),

					array(
						'name'    => __('Double Opt-in?', 'mailpoet-woocommerce-add-on'),
						'desc'    => __('Set the checkbox to be checked by default.', 'mailpoet-woocommerce-add-on'),
						'id'      => 'mailpoet_woocommerce_double_optin',
						'type'    => 'checkbox',
						'default' => '1',
						'class'   => 'single_list_only'
					),

					array(
						'name'        => __('Subscribe Checkbox label', 'mailpoet-woocommerce-add-on'),
						'desc'        => __('Enter your own label to place next to the subscription checkbox. Default: Yes, add me to your mailing list.', 'mailpoet-woocommerce-add-on'),
						'desc_tip'    => false,
						'id'          => 'mailpoet_woocommerce_checkout_label',
						'type'        => 'text',
						'placeholder' => __('Yes, add me to your mailing list.', 'mailpoet-woocommerce-add-on'),
						'class'       => 'single_list_only'
					),

					array(
						'type' => 'sectionend',
						'id'   => $this->id . '_general_options'
					),

				); // End general settings.
			}
			else{

				return array(

					array(
						'title' => __('Lists', 'mailpoet-woocommerce-add-on'),
						'type'  => 'title',
						'desc'  => __('Here lists all the campaigns you can assign the customers to when they subscribe. Simply tick the lists you want your customers to subscribe to and press "Save Changes".', 'mailpoet-woocommerce-add-on'),
						'id'    => $this->id . '_lists_options'
					),

					array(
						'type' => 'sectionend',
						'id'   => $this->id . '_lists_options'
					),

				); // End lists settings
			}
		} // get_settings()

		/**
		 * Output the settings
		 *
		 * @since  1.0.0
		 * @access public
		 * @global $current_section
		 */
		public function output() {
			global $current_section;

			$settings = $this->get_settings();

			WC_Admin_Settings::output_fields($settings);

			if( $current_section == 'lists' ){
				include_once('settings-newsletters.php');

				$mailpoet_list = mailpoet_lists();

				do_action('woocommerce_mailpoet_list_newsletters', $mailpoet_list);
			}
		} // END output()

		/**
		 * Save settings
		 *
		 * @since  1.0.0
		 * @access public
		 * @global $current_section
		 */
		public function save() {
			global $current_section;

			$settings = $this->get_settings();

			WC_Admin_Settings::save_fields($settings);

			if( $current_section == 'lists' ){
				// Each list that has been ticked will be saved.
				if( isset($_POST['checkout_lists']) ){
					$checkout_lists = $_POST['checkout_lists'];
					update_option('mailpoet_woocommerce_subscribe_too', $checkout_lists);
				}
				else{
					delete_option('mailpoet_woocommerce_subscribe_too');
				}
			}
		} // END save()

	} // END class

} // END if class exists

return new MailPoet_WooCommerce_Add_On_Settings();
