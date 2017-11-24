<?php
/**
 * Displays a table listing all lists created in MailPoet.
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  MailPoet WooCommerce Add-on
 * @license  GPL-2.0+
 * @since    1.0.0
 * @version  4.0.0
 */

if(! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Outputs the table listing the Newsletters lists.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $mailpoet_list
 * @return void
 */
function woocommerce_mailpoet_list_subscription_newsletters( $mailpoet_list ){
?>
<table class="mailpoet widefat" cellspacing="0">
	<thead>
		<tr>
			<th width="1%"><?php _e( 'Enabled', 'mailpoet-woocommerce-add-on' ); ?></th>
			<th><?php _e( 'Newsletters', 'mailpoet-woocommerce-add-on' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$checkout_lists = get_option( 'mailpoet_woocommerce_subscribe_too' );

	foreach ( $mailpoet_list as $key => $list ) {
		$list_id = $list[ 'list_id' ];
		$checked = '';

		if ( isset( $checkout_lists ) && ! empty( $checkout_lists ) ) {
			if ( in_array( $list_id, $checkout_lists ) ) { $checked = ' checked="checked"'; }
		}
		echo '<tr>
			<td width="1%" class="checkbox">
				<input type="checkbox" name="checkout_lists[]" value="' . esc_attr( $list_id ) . '"' . $checked . ' />
			</td>
			<td>
				<p><strong>' . $list[ 'name' ] . '</strong></p>
			</td>
		</tr>';
	}
	?>
	</tbody>
</table>
<?php
} // END woocommerce_mailpoet_list_subscription_newsletters()
add_action( 'woocommerce_mailpoet_list_newsletters', 'woocommerce_mailpoet_list_subscription_newsletters' );
