jQuery(window).load(function(){

	// Edit prompt
	jQuery(function(){
		var changed = false;
		jQuery('input, checkbox, select').change(function(){
			changed = true;
		});
		jQuery('.nav-tab-wrapper a').click(function(){
			if (changed) {
				window.onbeforeunload = function() {
					return mailpoet_wc_admin_params.i18n_nav_warning;
				}
			}
			else {
				window.onbeforeunload = '';
			}
		});
		jQuery('.submit input').click(function(){
			window.onbeforeunload = '';
		});
	});

	// Multi-Subscription option
	if ( jQuery( '#mailpoet_woocommerce_customer_selects' ).val() == 'yes' ) {
		jQuery( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).hide();
	} else {
		jQuery( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).show();
	}

	// If the Multi-Subscription option was changed.
	jQuery( '#mailpoet_woocommerce_customer_selects' ).change( function() {
		if ( jQuery( this ).val() == 'yes' ) {
			jQuery( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).hide();
		} else {
			jQuery( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).show();
		}
	}).change();

});
