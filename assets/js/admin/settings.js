/* global woocommerce_settings_params */
( function( $ ) {

	// Edit prompt
	$( function() {
		var changed = false;

		$( 'input, checkbox, select' ).change( function() {
			changed = true;
		});

		$( '.woo-nav-tab-wrapper a' ).click( function() {
			if (changed) {
				window.onbeforeunload = function() {
					return woocommerce_settings_params.i18n_nav_warning;
				};
			} else {
				window.onbeforeunload = '';
			}
		});

		$( '.submit input' ).click( function() {
			window.onbeforeunload = '';
		});
	});

	// Multi-Subscription option
	if ( $( '#mailpoet_woocommerce_customer_selects' ).val() == 'yes' ) {
		$( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).hide();
	} else {
		$( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).show();
	}

	// If the Multi-Subscription option was changed.
	$( '#mailpoet_woocommerce_customer_selects' ).change( function() {
		if ( $( this ).val() == 'yes' ) {
			$( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).hide();
		} else {
			$( '#mailpoet_woocommerce_checkout_label' ).closest( 'tr' ).show();
		}
	}).change();

})( jQuery );
