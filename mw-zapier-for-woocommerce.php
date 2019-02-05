<?php
/*
	Plugin Name: Zapier for WooCommerce
	Description: Send order info to zapier
	Version: 1.0
  Author: Santiago Fernandez P
	Text Domain: mw-zapier-for-woocommerce
	Domain Path: /languages


  WC requires at least: 2.2
  WC tested up to: 2.3
*/
if ( ! defined( 'WPINC' ) )  die;

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

  /**
   * Enqueue Admin Scripts
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
   */
  function mwzfw_enqueue_admin_scripts( $hook ) {

      if( is_admin() and $_GET[page] == 'mwzfw') {
          wp_enqueue_script(
            'mwzfw_admin_js',
            plugins_url( '/assets/admin.js', __FILE__ ),
            array( 'jquery' ),
            '1.0.0',
            true
          );
    }
  }

  add_action( 'admin_enqueue_scripts', 'mwzfw_enqueue_admin_scripts' );

  require_once 'includes/admin/menu-page.php';
  require_once 'includes/request.php';
}
