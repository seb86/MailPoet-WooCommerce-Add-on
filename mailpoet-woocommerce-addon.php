<?php
/*
Plugin Name: MailPoet WooCommerce Add-on
Plugin URI: http://www.mailpoet.com
Description: Adds a checkbox for your customers to subscribe to your MailPoet newsletters during checkout.
Version: 1.0.0
Author: Sebs Studio
Author URI: http://www.sebs-studio.com
Author Email: sebastien@sebs-studio.com
Requires at least: 3.5.1
Tested up to: 3.8

License:

  Copyright 2013 Sebs Studio (sebastien@sebs-studio.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

if(!defined('ABSPATH')) exit; // Exit if accessed directly

class MailPoet_WooCommerce_Add_on {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'MailPoet WooCommerce Add-on';
	const slug = 'mailpoet_woocommerce_add_on';

	/**
	 * Constructor
	 */
	public function __construct(){
		// Register an activation hook for the plugin
		register_activation_hook(__FILE__, array(&$this, 'install_mailpoet_woocommerce_add_on'));

		// Hook up to the init action
		add_action('init', array(&$this, 'init_mailpoet_woocommerce_add_on'));
	}

	/**
	 * Runs when the plugin is activated.
	 */
	function install_mailpoet_woocommerce_add_on(){
		add_option('mailpoet_woocommerce_enable_checkout', 0);
		add_option('mailpoet_woocommerce_checkout_label', __('Yes, add me to your mailing list', 'mailpoet_woocommerce'));
	}

	/**
	 * Runs when the plugin is initialized.
	 */
	public function init_mailpoet_woocommerce_add_on(){
		// Setup localization
		load_plugin_textdomain(self::slug, false, dirname(plugin_basename(__FILE__)).'/lang');

		if(is_admin()){
			add_filter('woocommerce_settings_tabs_array', array(&$this, 'add_settings_tab'));
			add_action('woocommerce_settings_tabs_mailpoet', array(&$this, 'mailpoet_newsletter_admin_settings'));

			// Save admin settings for each section.
			$sections = array('general', 'newsletters');
			foreach($sections as $section){
				add_action('woocommerce_update_options_mailpoet_'.$section, array(&$this, 'save_mailpoet_newsletter_admin_settings'));
			}
		}
		else{
			// hook into checkout page
			add_action('woocommerce_after_order_notes', array(&$this, 'on_checkout_page'));
		}
		// hook into order processing
		add_action('woocommerce_checkout_process', array(&$this, 'on_process_order'));
	}

	/**
	 * Add a tab to the settings page of WooCommerce for MailPoet.
	 *
	 * @access public
	 * @return void
	 */
	public function add_settings_tab($tabs){

		$tabs['mailpoet'] = __('MailPoet', 'mailpoet_woocommerce');

		return $tabs;
	}

	/**
	 * MailPoet settings page.
	 *
	 * Handles the display of the main woocommerce 
	 * settings page in admin.
	 *
	 * @access public
	 * @return void
	 */
	function mailpoet_newsletter_admin_settings(){
		global $woocommerce, $woocommerce_settings, $current_section, $current_tab;

		if(!current_user_can('manage_options')){
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		do_action('woocommerce_mailpoet_settings_start');

		include(dirname(__FILE__).'/include/settings.php');

		// Get current section
		$current_section = (empty($_REQUEST['section'])) ? 'general' : sanitize_text_field(urldecode($_REQUEST['section']));
		$current = $current_section ? '' : ' class="current"';

		// Creates each settings section.
		$mailpoet_settings = apply_filters('woocommerce_mailpoet_settings_sections', array(
								'general' => __('General', 'mailpoet_woocommerce'),
								'newsletters' => __('Newsletters', 'mailpoet_woocommerce'),
							));

		foreach($mailpoet_settings as $settings => $settings_title){
			$title = ucwords($settings_title);
			$current = $settings == $current_section ? ' class="current"' : '';
			$links[] = '<a href="'.add_query_arg('section', $settings, admin_url('admin.php?page=woocommerce_settings&tab=mailpoet')).'"'.$current.'>'.esc_html($title).'</a>';
		}

		echo '<ul class="subsubsub"><li>'.implode('| </li><li>', $links).'</li></ul><br class="clear" />';

		// Load the current section settings
		woocommerce_admin_fields($woocommerce_settings['mailpoet_'.$current_section]);

		if($current_section == 'newsletters'){
			include_once(dirname(__FILE__).'/include/settings-newsletters.php');

			$mailpoet_list = $this->mailpoet_lists();

			do_action('woocommerce_mailpoet_list_newsletters', $mailpoet_list);
		}
	}

	// Saves the settings for MailPoet Newsletter.
	public function save_mailpoet_newsletter_admin_settings(){
		global $woocommerce, $woocommerce_settings, $current_section;

		include(dirname(__FILE__).'/include/settings.php');
		include_once($woocommerce->plugin_path.'/admin/settings/settings-save.php');

		$current_section = (empty($_REQUEST['section'])) ? 'general' : sanitize_text_field(urldecode($_REQUEST['section']));

		woocommerce_update_options($woocommerce_settings['mailpoet_'.$current_section]);

		if($current_section == 'newsletters'){
			// Each list of newsletters that have been ticked will be saved.
			if(isset($_POST['checkout_lists'])){
				$checkout_lists = $_POST['checkout_lists'];
				$lists = $checkout_lists;
				update_option('mailpoet_woocommerce_subscribe_too', $lists);
			}
			else{
				delete_option('mailpoet_woocommerce_subscribe_too', $lists);
			}
		}
	}

	// Get all mailpoet lists.
	public function mailpoet_lists(){
		// This will return an array of results with the name and list_id of each mailing list
		$model_list = WYSIJA::get('list','model');
		$mailpoet_lists = $model_list->get(array('name','list_id'), array('is_enabled' => 1));

		return $mailpoet_lists;
	}

	/**
	 * This displays a checkbox field on the checkout 
	 * page to allow the customer to subscribe to newsletters.
	 */
	function on_checkout_page($checkout){
		// Checks if subscribe on checkout is enabled.
		$enable_checkout = get_option('mailpoet_woocommerce_enable_checkout');
		$checkout_label = get_option('mailpoet_woocommerce_checkout_label');

		if($enable_checkout == 'yes'){
			echo '<div id="mailpoet_checkout_field">';
			//echo apply_filters('mailpoet_woocommerce_subscribe_checkout_title', '<h3>'.__('Subscribe to Newsletter', 'mailpoet_woocommerce').'</h3>');
			woocommerce_form_field('mailpoet_checkout_subscribe', array( 
				'type' 			=> 'checkbox', 
				'class' 		=> array('mailpoet-checkout-class form-row-wide'), 
				'label' 		=> htmlspecialchars(stripslashes($checkout_label)), 
			), $checkout->get_value('mailpoet_checkout_subscribe'));
			echo '</div>';
		}
	}

	/**
	 * This process the customers subscription if any 
	 * to the newsletters along with their order.
	 */
	function on_process_order(){
		global $woocommerce;

		$mailpoet_checkout_subscribe = isset($_POST['mailpoet_checkout_subscribe']) ? 1 : 0;

		// If the check box has been ticked then the customer is added to the MailPoet lists enabled.
		if($mailpoet_checkout_subscribe == 1){
			$checkout_lists = get_option('mailpoet_woocommerce_subscribe_too');

			$user_data = array(
				'email' 	=> $_POST['billing_email'],
				'firstname' => $_POST['billing_first_name'],
				'lastname' 	=> $_POST['billing_last_name']
			);

			$data_subscriber = array(
				'user' 		=> $user_data,
				'user_list' => array('list_ids' => array($checkout_lists))
			);

			$userHelper = &WYSIJA::get('user','helper');
			$userHelper->addSubscriber($data_subscriber);
		}
	} // on_process_order()

} // end class
new MailPoet_WooCommerce_Add_on();

?>