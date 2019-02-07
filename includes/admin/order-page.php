<?php
if ( ! defined( 'WPINC' ) )  die;

function mwzfw_add_order_meta_box_action( $actions ) {
    global $theorder;

    $setting = get_option('mwzfw_settings');

    // bail if the order has been paid for or this action has been run
    if ( ! $theorder->is_paid() || is_null($setting['actionhook'])) {
        return $actions;
    }

    // add "mark printed" custom action
    $actions['mwzfw_action_hook'] = __( 'Trigger zapier hook', 'mw-zapier-for-woocommerce' );
    return $actions;
}
add_action( 'woocommerce_order_actions', 'mwzfw_add_order_meta_box_action' );


function mwzfw_process_order_meta_box_action( $order ) {
   	$setting = get_option('mwzfw_settings');

	$state = ($order->get_shipping_state() != '' ? $order->get_shipping_state() : $order->get_billing_state());
	$city = ($order->get_shipping_city() != '' ? $order->get_shipping_city() : $order->get_billing_city());
	$address_1 = ($order->get_shipping_address_1() != '' ? $order->get_shipping_address_1() : $order->get_billing_address_1());
	$address_2 = ($order->get_shipping_address_2() != '' ? $order->get_shipping_address_2() : $order->get_billing_address_2());
	$first_name = ($order->get_shipping_first_name() != '' ? $order->get_shipping_first_name() : $order->get_billing_first_name());
	$last_name = ($order->get_shipping_last_name() != '' ? $order->get_shipping_last_name() : $order->get_billing_last_name());

	$data = array(
		'store id' => $setting[store_id],
		'order id' => $order->get_id(),
		'payment method' => $order->get_payment_method(),
		'date created' => $order->get_date_created()->format ('d/m/Y'),
		'shipping date' => current_time( 'd/m/Y' ),
		'first name' => $first_name,
		'last name' => $last_name,
		'state' => $state,
		'city' => $city,
		'address 1' => $address_1,
		'address 2' => $address_2,
		'customer email' => $order->get_billing_email(),
		'customer phone' => $order->get_billing_phone(),
		'order_subtotal' => $order->get_subtotal(),
		'order_total_discount' => $order->get_total_discount(),
		'order_total_tax' => $order->get_total_tax(),
		'order_shipping_total' => $order->get_shipping_total(),
		'order_total' => $order->get_total(),
	);

	$metadata = $order->get_meta_data();

	foreach ($metadata as $singlemetadata) {
		$metadataarray = $singlemetadata->get_data();
		$data[$metadataarray[key]] = $metadataarray[value];
	}

	$json = json_encode($data);

	 wp_remote_post(
	    $setting['actionhook'],
	    array(
	      'body' => $json,
	    )
	  );
}
add_action( 'woocommerce_order_action_mwzfw_action_hook', 'mwzfw_process_order_meta_box_action' );