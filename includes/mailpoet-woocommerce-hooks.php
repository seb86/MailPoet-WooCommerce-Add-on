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

// Hook into the checkout page. Adds the subscription fields.
add_action('woocommerce_'.get_option('mailpoet_woocommerce_subscription_position'), 'on_checkout_page');

// Subscribe customer to the newsletters once the order has been made.
add_action('woocommerce_after_checkout_validation', 'on_process_order');
