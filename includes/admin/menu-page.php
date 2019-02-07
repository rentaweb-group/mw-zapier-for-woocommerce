<?php
if ( ! defined( 'WPINC' ) )  die;

if (!function_exists('mw_ecommerce_tools_page_html')) {
  function mw_ecommerce_tools_page_html () {
  	?>
  	<div class="wrap">
  		<h1><?php esc_html_e( get_admin_page_title() ); ?></h1>
  	</div>
  	<?php
  }
}

function mwzfw_options_page() {

	if (empty ( $GLOBALS['admin_page_hooks']['mw_ecommerce_tools'] ) ) {
		add_menu_page(
	        'Ecommerce Tools',
	        'Ecommerce Tools',
	        'manage_options',
	        'mw_ecommerce_tools',
	        'mw_ecommerce_tools_page_html',
	        'dashicons-cart',
	        9000
	    );
	}

	add_submenu_page(
	    'mw_ecommerce_tools',
	    'Ecommerce Tools',
	    'Zapier WooCommerce',
	    'manage_options',
	    'mwzfw',
	    'mwzfw_options_page_html'
	);
}
add_action( 'admin_menu', 'mwzfw_options_page' );

function mwzfw_options_page_html () {
   // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
      <h1><?php esc_html_e( 'Countdown Banner' , 'mw-zapier-for-woocommerce' ); ?></h1>
      <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "wporg_options"
        settings_fields( 'mwzfw' );
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections( 'mwzfw' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
      </form>
    </div>
    <?php

    $setting = get_option('mwzfw_settings');
}

function mwzfw_settings_init() {
	register_setting( 'mwzfw', 'mwzfw_settings');
	add_settings_section( 'mwzfw_settings_section',
                        __('Display Settings',
                        'mw-zapier-for-woocommerce'),
                        'mwzfw_settings_section_cb',
                        'mwzfw'
                      );

  add_settings_field( 'mwzfw_store_id',
                      __('Store id', 'mw-zapier-for-woocommerce'),
                      'mwzfw_store_id_cb',
                      'mwzfw',
                      'mwzfw_settings_section'
                    );

	add_settings_field( 'mwzfw_order_cod_field',
                      __('Hooks for completed (only COD) orders', 'mw-zapier-for-woocommerce'),
                      'mwzfw_order_cod_field_cb',
                      'mwzfw',
                      'mwzfw_settings_section'
                    );

  add_settings_field( 'mwzfw_order_field',
                      __('Hooks for completed (exclude COD) orders', 'mw-zapier-for-woocommerce'),
                      'mwzfw_order_field_cb',
                      'mwzfw',
                      'mwzfw_settings_section'
                    );
  add_settings_field( 'mwzfw_order_page_field',
                      __('Order page action hook', 'mw-zapier-for-woocommerce'),
                      'mwzfw_order_page_field_cb',
                      'mwzfw',
                      'mwzfw_settings_section'
                    );

}
add_action( 'admin_init', 'mwzfw_settings_init' );

function mwzfw_settings_section_cb () {
	esc_html_e( 'In this secction you can add the zapier hooks' , 'mw-zapier-for-woocommerce' );
}

function mwzfw_order_cod_field_cb() {
  $setting = get_option('mwzfw_settings');
  if (!$setting[codhooks]) {
    ?>
      <div>
        <label>Hook: </label>
        <input
          type="password"
          class="mwzfw-codhooks"
          index=0
          name="mwzfw_settings[codhooks][0]"
          value="<?php echo isset( $setting[codhooks][0] ) ? esc_attr( $setting[codhooks][0] ) : ''; ?>">
        <label>Description: </label>
        <input
          type="text"
          name="mwzfw_settings[codhooksdescription][0]"
          class="mwzfw-codhooks"
          index=0
          value="<?php echo isset( $setting[codhooksdescription][0] ) ? esc_attr( $setting[codhooksdescription][0] ) : ''; ?>">
      </div>
    <?php
  } else {

    foreach ($setting[codhooks] as $i => $codhook) {
      ?>
        <div>
          <label>Hook: </label>
          <input
            type="password"
            name="mwzfw_settings[codhooks][<?php echo $i; ?>]"
            value="<?php echo isset( $codhook ) ? esc_attr( $codhook ) : ''; ?>">
          <label>Description: </label>
          <input
            type="text"
            name="mwzfw_settings[codhooksdescription][<?php echo $i; ?>]"
            class="mwzfw-codhooks"
            index=<?php echo $i; ?>
            value="<?php echo isset( $setting[codhooksdescription][$i] ) ? esc_attr( $setting[codhooksdescription][$i] ) : ''; ?>">
          <span class="dashicons dashicons-dismiss mwzfw-remove-hook"></span>
        </div>

      <?php
    }
  }

  ?>
    <div class="mwzfw-add-cod-hook-line">
    </div>
    <div class="mwzfw-add-cod-hook">
      <span class="dashicons dashicons-plus-alt"></span>
    </div>
  <?php
}

function mwzfw_order_field_cb() {
  $setting = get_option('mwzfw_settings');
  if (!$setting[notcodhooks]) {
    ?>
      <div>
        <label>Hook: </label>
        <input
          type="password"
          class="mwzfw-notcodhooks"
          index=0
          name="mwzfw_settings[notcodhooks][0]"
          value="<?php echo isset( $setting[notcodhooks][0] ) ? esc_attr( $setting[notcodhooks][0] ) : ''; ?>">
        <label>Description: </label>
        <input
          type="text"
          name="mwzfw_settings[notcodhooksdescription][0]"
          class="mwzfw-notcodhooks"
          index=0
          value="<?php echo isset( $setting[notcodhooksdescription][0] ) ? esc_attr( $setting[notcodhooksdescription][0] ) : ''; ?>">
      </div>
    <?php
  } else {

    foreach ($setting[notcodhooks] as $i => $codhook) {
      ?>
        <div>
          <label>Hook: </label>
          <input
            type="password"
            name="mwzfw_settings[notcodhooks][<?php echo $i; ?>]"
            value="<?php echo isset( $codhook ) ? esc_attr( $codhook ) : ''; ?>">
          <label>Description: </label>
          <input
            type="text"
            name="mwzfw_settings[notcodhooksdescription][<?php echo $i; ?>]"
            class="mwzfw-notcodhooks"
            index=<?php echo $i; ?>
            value="<?php echo isset( $setting[notcodhooksdescription][$i] ) ? esc_attr( $setting[notcodhooksdescription][$i] ) : ''; ?>">
          <span class="dashicons dashicons-dismiss mwzfw-remove-hook"></span>
        </div>

      <?php
    }
  }

  ?>
    <div class="mwzfw-add-notcod-hook-line">
    </div>
    <div class="mwzfw-add-notcod-hook">
      <span class="dashicons dashicons-plus-alt"></span>
    </div>
  <?php
}

function mwzfw_store_id_cb() {
  $setting = get_option('mwzfw_settings');
  ?>
    <input
      type="text"
      name="mwzfw_settings[store_id]"
      value="<?php echo isset( $setting[store_id]) ? esc_attr( $setting[store_id]) : ''; ?>">
  <?php
}

function mwzfw_order_page_field_cb(){
  $setting = get_option('mwzfw_settings');
  ?>
     <div>
        <label>Hook: </label>
        <input
          type="password"
          name="mwzfw_settings[actionhook]"
          value="<?php echo isset( $setting[actionhook] ) ? esc_attr( $setting[actionhook] ) : ''; ?>">
        <label>Description: </label>
        <input
          type="text"
          name="mwzfw_settings[actionhookdescription]"
          value="<?php echo isset( $setting[actionhookdescription] ) ? esc_attr( $setting[actionhookdescription] ) : ''; ?>">
      </div>
  <?php
}
