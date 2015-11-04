<?php
/**
* Admin View: Admin MailPoet Requirment Notice
*/

if(! defined('ABSPATH')) exit; // Exit if accessed directly
?>
<div id="message" class="error">
	<p><?php echo sprintf( __('Hold on a minute. You need to <a href="'.wp_nonce_url( admin_url('/update.php?action=install-plugin&plugin=wysija-newsletters'), 'install-mailpoet', 'install_mailpoet_nonce').'">install MailPoet Newsletters</a> first to use <strong>%s</strong>.', 'mailpoet-woocommerce-add-on'), 'MailPoet WooCommerce Add-on', MAILPOET_WOOCOMMERCE_WP_VERSION_REQUIRED, 'MailPoet WooCommerce Add-On'); ?></p>
</div>
