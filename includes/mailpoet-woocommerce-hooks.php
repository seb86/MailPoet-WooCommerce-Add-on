<?php
/**
 * MailPoet WooCommerce Add-on Hooks
 *
 * Hooks for various functions used.
 *
 * @author   Sébastien Dumont
 * @category Core
 * @package  MailPoet WooCommerce Add-on/Functions
 * @license  GPL-2.0+
 * @since    1.0.0
 * @version  4.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$position = get_option( 'mailpoet_woocommerce_subscription_position' );

// Hook into the checkout page. Adds the subscription fields.
add_action( 'woocommerce_' . $position, 'on_checkout_page' );

// Subscribe customer to the newsletters once the order has been made.
add_action( 'woocommerce_after_checkout_validation', 'on_process_order' );
