<?php
/**
* Admin View: Admin WooCommerce Requirment Notice
*/

if(! defined('ABSPATH')) exit; // Exit if accessed directly
?>
<div id="message" class="error">
	<p><?php echo sprintf( __('Sorry, <strong>%s</strong> requires WooCommerce v'.MAILPOET_WOOCOMMERCE_WC_VERSION_REQUIRED.' or higher. Please update WooCommerce to the latest stable release. Thank you.', 'mailpoet-woocommerce-add-on'), 'MailPoet WooCommerce Add-on'); ?></p>
</div>
