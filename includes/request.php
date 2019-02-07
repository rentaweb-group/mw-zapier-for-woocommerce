<?php
if ( ! defined( 'WPINC' ) )  die;

function mwzfw_request($order_id){

  $setting = get_option('mwzfw_settings');
  $order = wc_get_order( $order_id );

  $state = ($order->get_shipping_state() != '' ? $order->get_shipping_state() : $order->get_billing_state());
	$city = ($order->get_shipping_city() != '' ? $order->get_shipping_city() : $order->get_billing_city());
	$address_1 = ($order->get_shipping_address_1() != '' ? $order->get_shipping_address_1() : $order->get_billing_address_1());
	$address_2 = ($order->get_shipping_address_2() != '' ? $order->get_shipping_address_2() : $order->get_billing_address_2());
	$first_name = ($order->get_shipping_first_name() != '' ? $order->get_shipping_first_name() : $order->get_billing_first_name());
	$last_name = ($order->get_shipping_last_name() != '' ? $order->get_shipping_last_name() : $order->get_billing_last_name());

  $data = array(
    'store id' => $setting[store_id],
    'order id' => $order_id,
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

  if ( $order->get_payment_method() == 'cod') {
    foreach ($setting[codhooks] as $i => $codhook) {
      wp_remote_post(
        $codhook,
        array(
          'body' => $json,
        )
      );
    }
  } else {
    foreach ($setting[notcodhooks] as $i => $codhook) {
      wp_remote_post(
        $codhook,
        array(
          'body' => $json,
        )
      );
    }
  }
  wp_die();
}
add_action( 'woocommerce_order_status_completed','mwzfw_request');
