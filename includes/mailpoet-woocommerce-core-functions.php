<?php
/**
 * MailPoet WooCommerce Add-on Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet WooCommerce Add-on/Functions
 * @version 	2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get all mailpoet lists.
function mailpoet_lists( ) {
	// This will return an array of results with the name and list_id of each mailing list
	$model_list = WYSIJA::get('list','model');
	$mailpoet_lists = $model_list->get(array('name','list_id'), array('is_enabled' => 1));

	return $mailpoet_lists;
}

/**
 * This displays a checkbox field on the checkout 
 * page to allow the customer to subscribe to newsletters.
 */
function on_checkout_page( $checkout ) {
	// Checks if subscribe on checkout is enabled.
	$enable_checkout = get_option('mailpoet_woocommerce_enable_checkout');
	$checkout_label = get_option('mailpoet_woocommerce_checkout_label');

	if($enable_checkout == 'yes'){
		echo '<div id="mailpoet_checkout_field">';
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
function on_process_order( ) {
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
			'user_list' => array('list_ids' => $checkout_lists )
		);

		$userHelper = &WYSIJA::get('user','helper');
		$userHelper->addSubscriber($data_subscriber);
	}
} // on_process_order()

if ( ! function_exists( 'is_ajax' ) ) {

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

?>