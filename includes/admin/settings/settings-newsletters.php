<?php
/**
 * Displays a table listing all lists created in MailPoet.
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly.

/**
 * Output the table list.
 *
 * @access public
 * @return void
 */
function woocommerce_mailpoet_list_subscription_newsletters($mailpoet_list){
	?>
	<tr valign="top">
		<td class="forminp" colspan="2">
			<table class="mailpoet widefat" cellspacing="0">
				<thead>
					<tr>
						<th width="1%"><?php _e('Enabled', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN); ?></th>
						<th><?php _e('Newsletters', MAILPOET_WOOCOMMERCE_TEXT_DOMAIN); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$checkout_lists = get_option('mailpoet_woocommerce_subscribe_too');
					foreach($mailpoet_list as $key => $list){
						$list_id = $list['list_id'];
						$checked = '';
						if(isset($checkout_lists) && !empty($checkout_lists)){
							if(in_array($list_id, $checkout_lists)){ $checked = ' checked="checked"'; }
						}
						echo '<tr>
							<td width="1%" class="checkbox">
								<input type="checkbox" name="checkout_lists[]" value="'.esc_attr($list_id).'"'.$checked.' />
							</td>
							<td>
								<p><strong>'.$list['name'].'</strong></p>
							</td>
						</tr>';
					}
					?>
				</tbody>
			</table>
		</td>
	</tr>
	<?php
}
add_action('woocommerce_mailpoet_list_newsletters', 'woocommerce_mailpoet_list_subscription_newsletters');
?>