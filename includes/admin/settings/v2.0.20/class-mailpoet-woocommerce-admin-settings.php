<?php
/**
 * MailPoet WooCommerce Add-on Settings
 *
 * @author   Sébastien Dumont
 * @category 	Admin
 * @package 	MailPoet WooCommerce Add-on/Admin
 * @version 	3.0.0
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(!class_exists('MailPoet_WooCommerce_Add_on_Admin_Settings')) {

/**
 * MailPoet_WooCommerce_Add_on_Admin_Settings
 */
class MailPoet_WooCommerce_Add_on_Admin_Settings {

	/**
	 * Constructor.
	 */
	public function __construct(){
		$this->id 		= 'mailpoet';
		$this->label 	= 'MailPoet';

		add_filter('woocommerce_settings_tabs_array', array( &$this, 'add_settings_tab' ) );
		add_action('woocommerce_settings_tabs_' . $this->id, array( &$this, 'output' ) );
		add_action('woocommerce_update_options_' . $this->id, array( &$this, 'save' ) );
		add_action('woocommerce_update_options_' . $this->id . '_general', array( &$this, 'save' ) );
		add_action('woocommerce_update_options_' . $this->id . '_lists', array( &$this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings(){
		global $current_section;

		if( $current_section != 'lists' ){

			return apply_filters($this->id . '_general_settings', array(

				array(
					'name' 	=> __('MailPoet Newsletter', mailpoet-woocommerce-add-on),
					'type' 	=> 'title',
					'desc' 	=> __('Now your customers can subscribe to newsletters you have created with MailPoet. Simply setup your settings below and press "Save Changes".', mailpoet-woocommerce-add-on),
					'id' 	=> $this->id . '_general_options'
				),

				array(
					'name' 			=> __('Enable subscribe on checkout', mailpoet-woocommerce-add-on),
					'desc' 			=> __('Add a subscribe checkbox to your checkout page.', mailpoet-woocommerce-add-on),
					'id' 			=> 'mailpoet_woocommerce_enable_checkout',
					'type' 			=> 'checkbox',
					'default' 		=> '1',
				),

				array(
					'name' 			=> __('Checkbox label', mailpoet-woocommerce-add-on),
					'desc' 			=> __('Enter a message to place next to the checkbox.', mailpoet-woocommerce-add-on),
					'desc_tip' 		=> true,
					'id' 			=> 'mailpoet_woocommerce_checkout_label',
					'type' 			=> 'text',
					'placeholder' 	=> __('Yes, add me to your mailing list', mailpoet-woocommerce-add-on),
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
					'title' 	=> __('Lists', mailpoet-woocommerce-add-on), 
					'type' 		=> 'title', 
					'desc' 		=> __('Here lists all the campaigns you can assign the customers to when they subscribe. Simply tick the lists you want your customers to subscribe to and press "Save Changes".', mailpoet-woocommerce-add-on), 
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
	 * Add a tab to the settings page of WooCommerce for Product Reviews Plus.
	 *
	 * @access public
	 * @return void
	 */
	public function add_settings_tab( $tabs ){
		$tabs[$this->id] = $this->label;

		return $tabs;
	}

	/**
	 * Output the settings
	 */
	public function output(){
		global $woocommerce, $woocommerce_settings, $current_section, $current_tab;

		if(!current_user_can('manage_woocommerce')){
			wp_die( __( 'You do not have sufficient permissions to access this page.', mailpoet-woocommerce-add-on ) );
		}

		do_action('woocommerce_mailpoet_settings_start');

		$mailpoet_settings = $this->get_settings();

		// Get current section
		$current_section = (empty($_REQUEST['section'])) ? 'general' : sanitize_text_field(urldecode($_REQUEST['section']));
		$current = $current_section ? '' : ' class="current"';

		// Creates each settings section.
		$mailpoet_section = apply_filters('woocommerce_mailpoet_settings_sections', array(
			'general' => __('General', mailpoet-woocommerce-add-on),
			'lists' => __('Lists', mailpoet-woocommerce-add-on),
		));

		foreach($mailpoet_section as $section => $title){
			$title = ucwords($title);
			$current = $section == $current_section ? ' class="current"' : '';
			$links[] = '<a href="'.add_query_arg('section', $section, admin_url('admin.php?page=woocommerce_settings&tab=mailpoet')).'"'.$current.'>'.esc_html($title).'</a>';
		}

		echo '<ul class="subsubsub"><li>'.implode('| </li><li>', $links).'</li></ul><br class="clear" />';

		woocommerce_admin_fields($mailpoet_settings);

		if($current_section == 'lists'){
			include_once(MailPoet_WooCommerce_Add_on()->plugin_path() . '/includes/admin/settings/settings-newsletters.php');

			$mailpoet_list = mailpoet_lists();

			do_action('woocommerce_mailpoet_list_newsletters', $mailpoet_list);
		}
	}

	/**
	 * Save settings
	 */
	public function save(){
		global $woocommerce, $woocommerce_settings, $current_section;

		$settings = $this->get_settings();

		include_once(MailPoet_WooCommerce_Add_on()->wc_plugin_path() . 'admin/settings/settings-save.php');

		$current_section = (empty($_REQUEST['section'])) ? 'general' : sanitize_text_field(urldecode($_REQUEST['section']));

		woocommerce_update_options($settings);

		if($current_section == 'lists'){
			// Each list of newsletters that have been ticked will be saved.
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

} // end if class exists.

?>