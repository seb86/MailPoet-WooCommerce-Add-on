<?php
/**
 * MailPoet WooCommerce Add-on Hooks
 *
 * Hooks for various functions used.
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet WooCommerce Add-on/Functions
 * @version 	2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// hook into checkout page - adds field after billing form.
//add_action('woocommerce_after_checkout_billing_form', array(&$this, 'on_checkout_page'));

// hook into checkout page - adds field after shipping form.
//add_action('woocommerce_after_checkout_shipping_form', array(&$this, 'on_checkout_page'));

// hook into checkout page - adds field after order notes.
add_action( 'woocommerce_after_order_notes', 'on_checkout_page' );

// hook into order processing
add_action( 'woocommerce_checkout_process', 'on_process_order' );

?>