<?php
/**
 * Defines the array of settings which are displayed in admin.
 *
 * Settings are defined here and displayed via functions.
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly.

$woocommerce_settings['mailpoet_general'] = apply_filters('woocommerce_mailpoet_general_settings', array(

	array(
		'name' 	=> __('MailPoet Newsletter', 'mailpoet_woocommerce'),
		'type' 	=> 'title',
		'desc' 	=> __('Now your customers can subscribe to newsletters you have created with MailPoet. Simple setup your settings below and press "Save Changes".', 'mailpoet_newsletters'),
		'id' 	=> 'woocommerce_mailpoet_general_options'
	),

	array(
		'name' 			=> __('Enable subscribe on checkout', 'mailpoet_woocommerce'),
		'desc' 			=> __('Add a subscribe checkbox to your checkout page.', 'mailpoet_woocommerce'),
		'id' 			=> 'mailpoet_woocommerce_enable_checkout',
		'type' 			=> 'checkbox',
		'default' 		=> '1',
	),

	array(
		'name' 			=> __('Checkbox label', 'mailpoet_woocommerce'),
		'desc' 			=> __('Enter a message to place next to the checkbox.', 'mailpoet_woocommerce'),
		'desc_tip' 		=> true,
		'id' 			=> 'mailpoet_woocommerce_checkout_label',
		'type' 			=> 'text',
		'placeholder' 	=> __('Yes, add me to your mailing list', 'mailpoet_woocommerce'),
	),

	array(
		'type' => 'sectionend', 
		'id' => 'woocommerce_mailpoet_general_options'
	),

)); // End general settings.

$woocommerce_settings['mailpoet_newsletters'] = apply_filters('woocommerce_mailpoet_newsletters_settings', array(

	array(
		'title' 	=> __('Newsletters', 'mailpoet_woocommerce'), 
		'type' 		=> 'title', 
		'desc' 		=> __('Here is the list of newsletters you can assign the customer to when they subscribe. Simply tick the newsletters you want your customers to subscribe to and press "Save Changes".', 'mailpoet_woocommerce'), 
		'id' 		=> 'woocommerce_mailpoet_newsletters_options'
	),

	array(
		'type' 		=> 'sectionend', 
		'id' 		=> 'woocommerce_mailpoet_newsletters_options'
	),

)); // End newsletter settings

?>