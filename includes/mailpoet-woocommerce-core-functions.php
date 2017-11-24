<?php
/**
 * MailPoet WooCommerce Add-on Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author   Sébastien Dumont
 * @category Core
 * @package  MailPoet WooCommerce Add-on/Functions
 * @since    1.0.0
 * @version  4.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Gets all enabled lists in MailPoet
 *
 * @since  1.0.0
 * @return array $mailpoet_lists
 */
if ( ! function_exists( 'mailpoet_lists' ) ) {
	function mailpoet_lists() {
		// This will return an array of results with the name and list_id of each mailing list
		$model_list = WYSIJA::get( 'list', 'model' );

		$mailpoet_lists = $model_list->get( array( 'name', 'list_id' ), array( 'is_enabled' => 1 ) );

		return $mailpoet_lists;
	} // END mailpoet_lists()
}

/**
 * This displays a checkbox field on the checkout
 * page to allow the customer to subscribe to newsletters.
 *
 * If the admin has enabled the customer to select the newsletters
 * then display a checkbox for each list available.
 *
 * @since   1.0.0
 * @version 4.0.0
 * @filter  mailpoet_woocommerce_subscription_section_title
 * @uses    woocommerce_form_field()
 * @uses    mailpoet_lists()
 * @uses    is_user_logged_in()
 * @uses    get_user_meta()
 * @uses    get_current_user_id()
 */
function on_checkout_page(){
	$enable_checkout    = get_option( 'mailpoet_woocommerce_enable_checkout' ); // Is the add-on enabled?
	$customer_selects   = get_option( 'mailpoet_woocommerce_customer_selects' ); // Multi-Subscriptions
	$checkbox_status    = get_option( 'mailpoet_woocommerce_checkbox_status' ); // Checkbox Status
	$subscription_lists = get_option( 'mailpoet_woocommerce_subscribe_too' ); // Subscription Lists selected
	$field_value        = $checkbox_status == 'checked' ? 1 : 0; // Field Value

	/**
	 * If the add-on is enabled and at least one list has been
	 * selected. Display the subscription fields on the
	 * checkout page.
	 */
	if ( $enable_checkout == 'yes' && ! empty( $subscription_lists ) ) {

		/**
		 * Prevents the subscribe field showing on the checkout page
		 * if the logged in user has already subscribed.
		 *
		 * ONLY - If the customer does not have the choice of selecting the newsletter to join.
		 */
		if ( is_user_logged_in() && $customer_selects != 'yes' && get_user_meta( get_current_user_id(), '_mailpoet_wc_subscribed_to_newsletter', true ) ) {
			return false;
		}

		echo '<div id="mailpoet_subscription_section">';

		echo '<h3>' . apply_filters( 'mailpoet_woocommerce_subscription_section_title', __( 'Subscribe to Newsletter', 'mailpoet-woocommerce-add-on' ) ) . '</h3>';

		do_action( 'woocommerce_mailpoet_checkout_before_subscribe' );

		// Customer can select more than one newsletter.
		if ( $customer_selects == 'yes' ) {
			foreach( mailpoet_lists() as $key => $list ) {
				$list_id   = $list['list_id']; // List ID number
				$list_name = $list['name']; // List Name

				// Checks if the list was selected in the add-on settings before showing the checkbox field.
				if ( in_array( $list_id, $subscription_lists ) ) {
					mailpoet_woocommerce_form_field( 'mailpoet_checkout_subscription', array(
						'id'     => 'list-' . $list_id,
						'type'   => 'multicheckbox',
						'class'  => array( 'mailpoet-checkout-class form-row-wide' ),
						'label'  => htmlspecialchars( stripslashes( $list_name ) ),
						'status' => $checkbox_status
					), $list_id );
				}
			}
		}
		/**
		* The customer can simply subscribe to the enabled
		* newsletters the admin set in the settings.
		*/
		else {
			$checkout_label = get_option( 'mailpoet_woocommerce_checkout_label' ); // Subscribe Checkbox Label
			$checkout_label = ! empty( $checkout_label ) ? $checkout_label : __( 'Yes, please subscribe me to the newsletter.', 'mailpoet-woocommerce-add-on' ); // Puts default label if not set in the settings.

			woocommerce_form_field( 'mailpoet_checkout_subscribe', array(
				'type'    => 'checkbox',
				'class'   => array( 'mailpoet-checkout-class form-row-wide' ),
				'label'   => htmlspecialchars( stripslashes( $checkout_label ) ),
			), $field_value );

		} // END if $customer_selects

		do_action( 'woocommerce_mailpoet_checkout_after_subscribe' );

		echo '</div>';

	} // END if $enabled_checkout
} // END on_checkout_page()

/**
 * This process the customers subscription if any to
 * the newsletters selected once the order has been made.
 *
 * @since   1.0.0
 * @version 4.0.0
 * @uses    is_user_logged_in()
 * @uses    get_user_meta()
 * @uses    get_current_user_id()
 * @filter  mailpoet_woocommerce_subscribe_error
 * @filter  mailpoet_woocommerce_subscribe_confirm
 * @filter  mailpoet_woocommerce_display_confirm_notice
 * @filter  mailpoet_woocommerce_display_thank_you_notice
 * @filter  mailpoet_woocommerce_subscribe_thank_you
 * @uses    add_user_meta()
 */
function on_process_order(){
	$mailpoet_checkout_subscribe = isset( $_POST['mailpoet_checkout_subscribe'] ) ? 1 : 0;

	$subscription_lists = '';
	$subscribe_customer = false; // Default to false unless told otherwise.

	// If the checkbox has been ticked then the customer is added to the MailPoet lists enabled.
	if ( $mailpoet_checkout_subscribe == 1 ) {
		$subscription_lists = get_option( 'mailpoet_woocommerce_subscribe_too' );
		$subscribe_customer = true;
	}

	// If the customer selected one or more lists, then the customer is added to those MailPoet lists only.
	if ( isset( $_POST['mailpoet_checkout_subscription' ] ) && is_array( $_POST['mailpoet_checkout_subscription'] ) ) {
		$subscription_lists = $_POST['mailpoet_checkout_subscription']; // Returns multiple lists selected.
		$subscribe_customer = true;
	}

	// If the user is logged in and has already subscribed, don't proceed any further.
	if ( is_user_logged_in() && get_user_meta( get_current_user_id(), '_mailpoet_wc_subscribed_to_newsletter', true ) ) {
		$subscribe_customer = false;

		return false;
	}

	/**
	 * If the customer does not want to subscribe
	 * or has already subscribed, we ignore the rest.
	 */
	if ( ! $subscribe_customer ) {
		return false;
	}

	$user_data = array(
		'email'     => $_POST['billing_email'],
		'firstname' => $_POST['billing_first_name'],
		'lastname'  => $_POST['billing_last_name']
	);

	$data_subscriber = array(
		'user'      => $user_data,
		'user_list' => array('list_ids' => $subscription_lists)
	);

	$user_helper = WYSIJA::get('user','helper');

	// Double Opt-in Option
	$double_optin = get_option('mailpoet_woocommerce_double_optin');

	if ( isset( $double_optin ) && $double_optin == 'yes' ) {
		$user_helper->addSubscriber( $data_subscriber, true ); // Add the subscriber but unconfirmed.

		$user_model = WYSIJA::get( 'user', 'model' );
		$subscriber_id = $user_model->getOne( false, array(
			'email' => trim( $user_data['email'] )
		) );

		/**
		 * If the customer was not added as a subscriber due to
		 * a complication with MailPoet, let the customer know.
		 */
		if ( $subscriber_id == 0 || empty( $subscriber_id ) ) {
			wc_add_notice( apply_filters( 'mailpoet_woocommerce_subscribe_error', __( 'There appears to be a problem subscribing you to our newsletter. Please let us know so we can manually add you ourselves. Thank you.', 'mailpoet-woocommerce-add-on' ) ), 'error' );

			return false;
		}

		// This makes sure the customer is not subscribed already.
		$user_model->update(
			array( 'status' => -1 ),
			array( 'user_id' => get_current_user_id()
		) );

		// Send confirmation email.
		$user_helper->sendConfirmationEmail( $subscriber_id, true, $subscription_lists );

		// Display a notice to the customer.
		if ( apply_filters( 'mailpoet_woocommerce_display_confirm_notice', true ) ) {
			wc_add_notice( apply_filters( 'mailpoet_woocommerce_subscribe_confirm', __( 'We have sent you an email to confirm your newsletter subscription. Thank you.', 'mailpoet-woocommerce-add-on' ) ) );
		}
	}
	else {
		$user_helper->addSubscriber( $data_subscriber, false ); // Add the subscriber and confirm automatically.
		$user_helper->confirm_user(); // Confirm user

		$user_model    = WYSIJA::get( 'user', 'model' );
		$subscriber_id = $user_model->getOne( false, array(
			'email' => trim( $user_data['email'] )
		) );

		/**
		 * If the customer was not added as a subscriber due to
		 * a complication with MailPoet, let the customer know.
		 */
		if ( $subscriber_id == 0 || empty( $subscriber_id ) ) {
			wc_add_notice( apply_filters( 'mailpoet_woocommerce_subscribe_error', __( 'There appears to be a problem subscribing you to our newsletter. Please let us know so we can manually add you ourselves. Thank you.', 'mailpoet-woocommerce-add-on' ) ), 'error' );

			return false;
		}

		// Display a notice to the customer.
		if ( apply_filters( 'mailpoet_woocommerce_display_thank_you_notice', true ) ) {
			wc_add_notice( apply_filters( 'mailpoet_woocommerce_subscribe_thank_you', __( 'Thank you for subscribing to our newsletter.', 'mailpoet-woocommerce-add-on' ) ) );
		}
	}

	// Now that the user has subscribed, lets update the customers profile so we don't forget.
	add_user_meta( get_current_user_id(), '_mailpoet_wc_subscribed_to_newsletter', true );
} // on_process_order()

if ( ! function_exists( 'mailpoet_woocommerce_form_field' ) ) {
	/**
	 * Outputs a multicheckbox field for the checkout form.
	 *
	 * @param string $key
	 * @param mixed  $args
	 * @param string $value (default: null)
	 */
	function mailpoet_woocommerce_form_field( $key, $args, $value = null ) {
		$defaults = array(
			'type'              => 'text',
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'id'                => $key,
			'class'             => array(),
			'label_class'       => array(),
			'input_class'       => array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'validate'          => array(),
			'default'           => '',
		);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'mailpoet-woocommerce-add-on'  ) . '">*</abbr>';
		}
		else {
			$required = '';
		}

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( is_string( $args['label_class'] ) ) {
			$args['label_class'] = array( $args['label_class'] );
		}

		if ( is_null( $value ) ) {
			$value = $args['default'];
		}

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		if ( ! empty( $args['validate'] ) ) {
			foreach( $args['validate'] as $validate ) {
				$args['class'][] = 'validate-' . $validate;
			}
		}

		$field           = '';
		$label_id        = $args['id'];
		$field_container = '<p class="form-row %1$s" id="%2$s">%3$s</p>';

		switch( $args['type'] ) {
			case 'multicheckbox' :

				$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . implode( ' ', $custom_attributes ) . '>'.
				'<input type="checkbox" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '[]" id="' . esc_attr( $args['id'] ) . '" value="' . $value . '"';

				if ( $args['status'] == 'checked' ) { $field .= ' checked="checked"'; }

				$field .= '  /> ' . $args['label'] . $required . '</label>';

				break;
		}

		if ( ! empty( $field ) ) {
			$field_html = '';

			if ( $args['label'] && 'multicheckbox' != $args['type'] ){
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
			}

			$field_html .= $field;

			if ( $args['description'] ) {
				$field_html .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
			}

			$container_class = 'form-row ' . esc_attr( implode( ' ', $args['class'] ) );
			$container_id = esc_attr( $args['id'] ) . '_field';

			$after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

			$field = sprintf( $field_container, $container_class, $container_id, $field_html ) . $after;
		}

		$field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

		if ( $args['return'] ) {
			return $field;
		}
		else {
			echo $field;
		}
	}
} // END mailpoet_woocommerce_form_field()
