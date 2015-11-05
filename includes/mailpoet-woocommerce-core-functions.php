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
 *
 * @since   1.0.0
 * @version 3.0.0
 * @param   $checkout
 * @filter  mailpoet_woocommerce_subscription_section_title
 * @uses    woocommerce_form_field()
 * @uses    mailpoet_lists()
 * @uses    is_user_logged_in()
 * @uses    get_user_meta()
 * @uses    get_current_user_id()
 */
function on_checkout_page( $checkout ) {
	// Checks if subscribe on checkout is enabled.
	$enable_checkout    = get_option('mailpoet_woocommerce_enable_checkout'); // Is the add-on enabled?
	$customer_selects   = get_option('mailpoet_woocommerce_customer_selects'); // Multi-Subscriptions
	$checkbox_status    = get_option('mailpoet_woocommerce_checkbox_status'); // Checkbox Status
	$subscription_lists = get_option('mailpoet_woocommerce_subscribe_too'); // Subscription Lists selected
	$field_value        = $checkbox_status == 'checked' ? 1 : 0; // Field Value

	if( $enable_checkout == 'yes' ) {

		// If the user is logged in and has already subscribed, don't show the subscription fields.
		if ( is_user_logged_in() && get_user_meta( get_current_user_id(), '_mailpoet_wc_subscribed_to_newsletter', true ) ) {
			return;
		}

		echo '<div id="mailpoet_subscription_section">';

		echo '<h3>'.apply_filters('mailpoet_woocommerce_subscription_section_title', __('Subscribe to Newsletter/s', 'mailpoet-woocommerce-add-on')).'</h3>';

		// Customer can select more than one newsletter.
		if( $customer_selects == 'yes' ) {

			foreach( mailpoet_lists() as $key => $list ){

				$list_id    = $list['list_id']; // List ID number
				$list_name  = $list['name']; // List Name
				$field_name = 'mailpoet_checkout_subscription[]'; // Field Name
				$field_id   = 'mailpoet_checkout_subscription'; // Field ID

				// Checks if the list was selected in the add-on settings before showing the checkbox field.
				if( in_array($list_id, $subscription_lists) ) {
					woocommerce_form_field($field_name, array(
						'id'    => $field_id,
						'type'  => 'checkbox',
						'class' => array('mailpoet-checkout-class form-row-wide'),
						'label' => htmlspecialchars(stripslashes($list_name)),
					), $field_value);
				}

			}

		}
		/**
		* The customer can simply subscribe to the enabled
		* newsletters the admin set in the settings.
		*/
		else {
			$checkout_label = get_option('mailpoet_woocommerce_checkout_label'); // Subscribe Checkbox Label
			$checkout_label = !empty( $checkout_label ) ? $checkout_label : __('Yes, please subscribe me to the newsletter/s.', 'mailpoet-woocommerce-add-on'); // Puts default label if not set in the settings.

			$field_name = 'mailpoet_checkout_subscribe'; // Field Name

			woocommerce_form_field($field_name, array(
				'type'    => 'checkbox',
				'class'   => array('mailpoet-checkout-class form-row-wide'),
				'label'   => htmlspecialchars(stripslashes($checkout_label)),
			), $field_value);

		} // END if $customer_selects

		echo '</div>';

	} // END if $enabled_checkout
} // END on_checkout_page()

/**
 * This process the customers subscription if any to
 * the newsletters selected once the order has been made.
 *
 * @since   1.0.0
 * @version 3.0.0
 * @uses    add_user_meta()
 */
function on_process_order(){
	$mailpoet_checkout_subscribe    = isset( $_POST['mailpoet_checkout_subscribe'] ) ? 1 : 0;
	$mailpoet_checkout_subscription = $_POST['mailpoet_checkout_subscription'];

	$subscribe_customer = false;

	// If the checkbox has been ticked then the customer is added to the MailPoet lists enabled.
	if( $mailpoet_checkout_subscribe == 1 ) {
		$subscription_lists = get_option('mailpoet_woocommerce_subscribe_too');
		$subscribe_customer = true;
	}

	// If the customer selected one or more lists, then the customer is added to those MailPoet lists only.
	if( isset($mailpoet_checkout_subscription) && is_array($mailpoet_checkout_subscription)) {
		$subscription_lists = $mailpoet_checkout_subscription;
		$subscribe_customer = true;

		$user_data = array(
			'email'     => $_POST['billing_email'],
			'firstname' => $_POST['billing_first_name'],
			'lastname'  => $_POST['billing_last_name']
		);

		$data_subscriber = array(
			'user'      => $user_data,
			'user_list' => array('list_ids' => $subscription_lists)
		);

		$userHelper = &WYSIJA::get('user','helper');
		$userHelper->addSubscriber($data_subscriber);

		// Now that the user has subscribed, lets update the customers profile so we don't forget.
		add_user_meta( get_current_user_id(), '_mailpoet_wc_subscribed_to_newsletter', true );
	}
} // on_process_order()
