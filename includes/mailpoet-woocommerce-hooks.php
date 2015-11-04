<?php
/**
 * MailPoet WooCommerce Add-on Hooks
 *
 * Hooks for various functions used.
 *
 * @since    1.0.0
 * @author   Sébastien Dumont
 * @category Core
 * @package  MailPoet WooCommerce Add-on/Functions
 * @version  3.0.0
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

// hook into checkout page - adds field before billing form.
add_action('woocommerce_before_checkout_billing_form', 'on_checkout_page');

// hook into checkout page - adds field after billing form.
add_action('woocommerce_after_checkout_billing_form', 'on_checkout_page');

// hook into checkout page - adds field before shipping form.
add_action('woocommerce_before_checkout_shipping_form', 'on_checkout_page');

// hook into checkout page - adds field after shipping form.
add_action('woocommerce_after_checkout_shipping_form', 'on_checkout_page');

// hook into checkout page - adds field before order notes.
add_action( 'woocommerce_before_order_notes', 'on_checkout_page' );

// hook into checkout page - adds field after order notes.
add_action( 'woocommerce_after_order_notes', 'on_checkout_page' );

// hook into order processing
add_action( 'woocommerce_checkout_process', 'on_process_order' );
