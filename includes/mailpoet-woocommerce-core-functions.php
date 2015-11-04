<?php
/**
 * MailPoet WooCommerce Add-on Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @since    1.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  MailPoet WooCommerce Add-on/Functions
 * @version  3.0.0
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Gets all enabled lists in MailPoet
 */
if ( !function_exists('mailpoet_lists')) {
	function mailpoet_lists(){
		// This will return an array of results with the name and list_id of each mailing list
		$model_list = WYSIJA::get('list','model');
		$mailpoet_lists = $model_list->get(array('name','list_id'), array('is_enabled' => 1));

		return $mailpoet_lists;
	}
}

/**
 * This displays a checkbox field on the checkout
 * page to allow the customer to subscribe to newsletters.
 *
 * If the admin has enabled the customer to select the newsletters
 * then display a checkbox for each list available.
 */
function on_checkout_page( $checkout ) {
	// Checks if subscribe on checkout is enabled.
	$enable_checkout  = get_option('mailpoet_woocommerce_enable_checkout');
	$customer_selects = get_option('mailpoet_woocommerce_customer_selects');
	$double_optin     = get_option('mailpoet_woocommerce_double_optin');
	$checkout_label   = get_option('mailpoet_woocommerce_checkout_label');
	if( isset( $checkout_label ) ) $checkout_label ? $checkout_label : __('Yes, add me to your mailing list.', 'mailpoet-woocommerce-add-on');

	if( $enable_checkout == 'yes' ) {

		echo '<div id="mailpoet_checkout_field">';

		// Customer can select more than one newsletter.
		if( $customer_selects == 'yes' ) {

			foreach( mailpoet_lists() as $key => $list ){

				$list_id = $list['list_id']; // List ID number
				$list_name = $list['name']; // List Name
				$field_name = 'mailpoet_checkout_subscribe_selected[]'; // Field Name

				woocommerce_form_field($field_name, array(
					'type'  => 'checkbox',
					'class' => array('mailpoet-checkout-class form-row-wide'),
					'label' => htmlspecialchars(stripslashes($list_name)),
				), $checkout->get_value($list_id));

			}

		}
		/**
		* The customer can simply subscribe to the enabled
		* newsletters the admin set in the settings.
		*/
		else {

			$field_name = 'mailpoet_checkout_subscribe'; // Field Name

			woocommerce_form_field($field_name, array(
				'type'    => 'checkbox',
				'class'   => array('mailpoet-checkout-class form-row-wide'),
				'label'   => htmlspecialchars(stripslashes($checkout_label)),
				'default' => $double_optin,
			), $checkout->get_value($field_name));

		} // END if $customer_selects

		echo '</div>';

	} // END if $enabled_checkout
} // END on_checkout_page()

/**
 * This process the customers subscription if any
 * to the newsletters along with their order.
 */
function on_process_order(){
	$mailpoet_checkout_subscribe = isset( $_POST['mailpoet_checkout_subscribe'] ) ? 1 : 0;

	$mailpoet_checkout_subscribe_selected = $_POST['mailpoet_checkout_subscribe_selected'];

	$subscribe_customer = false;

	// If the checkbox has been ticked then the customer is added to the MailPoet lists enabled.
	if( $mailpoet_checkout_subscribe == 1 ) {
		$checkout_lists = get_option('mailpoet_woocommerce_subscribe_too');
		$subscribe_customer = true;
	}

	// If the customer selected one or more lists, then the customer is added to those MailPoet lists only.
	if( isset( $mailpoet_checkout_subscribe_selected ) ) {
		$checkout_lists = $mailpoet_checkout_subscribe_selected;
		$subscribe_customer = true;

		$user_data = array(
			'email'     => $_POST['billing_email'],
			'firstname' => $_POST['billing_first_name'],
			'lastname'  => $_POST['billing_last_name']
		);

		$data_subscriber = array(
			'user'      => $user_data,
			'user_list' => array('list_ids' => $checkout_lists )
		);

		$userHelper = &WYSIJA::get('user','helper');
		$userHelper->addSubscriber($data_subscriber);
	}
} // on_process_order()

if ( !function_exists('is_ajax')) {
	/**
	 * is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @access public
	 * @return bool
	 */
	function is_ajax() {
		if ( defined('DOING_AJAX') ) {
			return true;
		}

		return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ? true : false;
	}
}
