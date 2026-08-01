<?php
/*
 * Plugin Name: WooCommerce BACS Receipt Upload
 * Plugin URI: https://github.com/pooyanshabani
 * Description: A WooCommerce plugin that extends Direct Bank Transfer (BACS) by collecting payment receipts, transaction IDs, proof images, and bank account information.
 * Author: Pooyan Shabani
 * Author URI: https://github.com/pooyanshabani
 * Text Domain: c2cp-td-woocommerce
 * Domain Path: /languages
 * Version: 1.0.0
 * Requires at least: 7.0
 * Requires PHP:      8.0
 */


//remove if direct
defined('ABSPATH') || exit;
//set version
define('C2CP_VER', '1.0.0');
//set assets images,css & js  folders
define('C2CP_RTD_ASSEST_URL', plugin_dir_url(__FILE__) . 'assets/');
define('C2CP_RTD_IMAGES_URL', C2CP_RTD_ASSEST_URL . 'img/');
define('C2CP_RTD_CSS_URL', C2CP_RTD_ASSEST_URL . 'css/');
define('C2CP_RTD_JS_URL', C2CP_RTD_ASSEST_URL . 'js/');

define('C2CP_RTD_IMAGES_PATH', plugin_dir_path(__FILE__) . '/assets/img/');

//set assets libs & view folders
define('C2CP_INC', plugin_dir_path(__FILE__) . 'inc/');

//JS CSS VER
define('C2CP_JSCCS_VER', '1.0.0');
define('C2CP_JSCCS_ASSEST_VER', defined('WP_DEBUG') && WP_DEBUG ? time() : C2CP_JSCCS_VER );

//include notificator 
include(C2CP_INC . 'c2cp_notificator.php');

//check php & wp version when plugin active
register_activation_hook(__FILE__, function () {

    $php = '8';
    $wp = '7.0';

    global $wp_version;

    if (version_compare($wp_version, $wp, '<')) {

        wp_die(
            sprintf( __('You must have atleast wordpress version %s your curent version is %s', 'c2cp-td-woocommerce'), $wp, $wp_version)
        );
    }

    if (version_compare(PHP_VERSION, $php, '<')) {

        wp_die(
            sprintf( __('You must have atleast php version %s', 'c2cp-td-woocommerce'), $php)
        );

    }
	if (!is_plugin_active('woocommerce/woocommerce.php')){
		wp_die(
			__('WooCommerce plugin is not installed/activated! To use the this plugin, first install and activate WooCommerce', 'c2cp-td-woocommerce')
        );
	}

	notificator_send_message_c2cp_plugin_active('Plugin C2C Activated at ' . home_url());

});

//when plugin deactive
register_deactivation_hook(__FILE__, function () {

    notificator_send_message_c2cp_plugin_active('Plugin C2C Deactivated at ' . home_url());
});

//add text domain action
add_action('plugins_loaded', function () {
	load_plugin_textdomain(
		'c2cp-td-woocommerce',
		false,
		dirname( plugin_basename(__FILE__) ) . '/languages'
	);
});

//add css & js files in admin
add_action('admin_enqueue_scripts', function () {
global $pagenow;
	if ($pagenow === 'admin.php' && isset($_GET['page']) && isset($_GET['action']) && $_GET['action'] === 'edit' ){
		
		wp_enqueue_media();

		wp_enqueue_script(
			'c2cp-admin-script-v2',
			C2CP_RTD_JS_URL . 'c2cp_script_admin-v2.js',
			['jquery', 'wp-mediaelement', 'media-upload'],
			C2CP_JSCCS_ASSEST_VER,
		);

		$c2c_settingsjs_v2 = [
			'c2cp_card'		=> C2CP_RTD_IMAGES_URL . 'c2cp_card_del.jpg',
		];

		wp_localize_script ('c2cp-admin-script-v2', 'c2cp_admin_settingsjs_v2', $c2c_settingsjs_v2);

		wp_enqueue_style(
			'c2cp-admin-style',
			C2CP_RTD_CSS_URL . 'c2cp_style_admin.css',
			[],
			C2CP_JSCCS_ASSEST_VER
		);
	}

	if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wc-settings' && isset($_GET['tab']) && $_GET['tab'] === 'checkout' && isset($_GET['section']) && $_GET['section'] === 'c2cp-settings'){

		wp_enqueue_script(
			'c2cp-admin-script',
			C2CP_RTD_JS_URL . 'c2cp_script_admin.js',
			['jquery', 'wp-mediaelement', 'media-upload'],
			C2CP_JSCCS_ASSEST_VER,
		);

		$c2c_settingsjs = [
			'button_title'		=> __( 'Reset', 'c2cp-td-woocommerce' ),
			'button_confirm'	=> __( 'Are you sure you want to reset settings?', 'c2cp-td-woocommerce' ),
		];

		wp_localize_script ('c2cp-admin-script', 'c2cp_admin_settingsjs', $c2c_settingsjs);

		wp_enqueue_style(
			'c2cp-admin-style',
			C2CP_RTD_CSS_URL . 'c2cp_style_admin.css',
			[],
			C2CP_JSCCS_ASSEST_VER
		);
	}
});

//add css & js files in front
add_action('wp_enqueue_scripts', function () {
		wp_enqueue_script(
			'c2cp-view-script',
			C2CP_RTD_JS_URL . 'c2cp_script.js',
			['jquery'],
			C2CP_JSCCS_ASSEST_VER,
			true
		);

		wp_enqueue_style(
			'c2cp-view-style',
			C2CP_RTD_CSS_URL . 'c2cp_style.css',
			[],
			C2CP_JSCCS_ASSEST_VER
		);
		$c2cp_settings_deskcol_thp = get_option('c2cp_settings_deskcol_thp');
		$c2cp_settings_deskcol = get_option('c2cp_settings_deskcol');
		$c2cp_settings_mobilecol = get_option('c2cp_settings_mobilecol');
		$c2cp_settings_fontsize = get_option('c2cp_settings_fontsize') . 'px';
		if ($c2cp_settings_mobilecol || $c2cp_settings_deskcol) {
			if($c2cp_settings_deskcol === '1') {
				$css_deskcol_r = 3; $css_deskcol_s = 4;
			} else { 
				$css_deskcol_r = $c2cp_settings_deskcol;
				$css_deskcol_s = $c2cp_settings_deskcol;
			}
			if($c2cp_settings_deskcol_thp === '1') {
				$css_deskcol_thp_r = 3; 
			} else { 
				$css_deskcol_thp_r = $c2cp_settings_deskcol_thp;
			}
			if($c2cp_settings_mobilecol === '1') {
				$css_mobkcol_r = 3; $css_mobkcol_s = 4;
			} else { 
				$css_mobkcol_r = $c2cp_settings_mobilecol;
				$css_mobkcol_s = $c2cp_settings_mobilecol;
			}
			wp_add_inline_style(
			'c2cp-view-style',
			"section.c2cp-bacs-bank-fdetails-thp {
				grid-template-columns: repeat($css_deskcol_thp_r, 1fr);
			}
			p.c2cp-display-thankyoupage-notice {
				font-size : $c2cp_settings_fontsize;
				}
			section.c2cp-bacs-bank-fdetails {
				grid-template-columns: repeat($css_deskcol_r, 1fr);
			}
			.c2cp-bacs-bank-details {
				grid-column: span $css_deskcol_s;
			}
			@media screen and (min-width: 300px) and (max-width: 550px) {
				section.c2cp-bacs-bank-fdetails, section.c2cp-bacs-bank-fdetails-thp {
				grid-template-columns: repeat($css_mobkcol_r, 1fr);
				}
				.c2cp-bacs-bank-details {
					grid-column: span $css_mobkcol_s;
				}
			}
			section.woocommerce-bacs-bank-details {display: none;}"
        	);
		}
});


include(C2CP_INC . 'c2cp_settings.php');
include(C2CP_INC . 'c2cp_reset_settings.php');

global $wpdb;

if (get_option('c2cp_settings_page')) {
	$c2cp_settings_page = get_option('c2cp_settings_page');
	} else {
		$c2cp_settings_page = 'checkout';
}


if ($c2cp_settings_page == 'checkout') { 
	include(C2CP_INC . 'c2cp_checkout.php');
}
if ($c2cp_settings_page == 'thankyou') { 
	include(C2CP_INC . 'c2cp_thankyou.php');
}



function c2c_wc_get_order_data( $order_id, $meta_key ) {
    global $wpdb;

 
    $table_name = $wpdb->prefix . 'wc_orders_meta';
    $table_exists = $wpdb->get_var( $wpdb->prepare(
        "SHOW TABLES LIKE %s",
        $table_name
    ) );

    if ( $table_exists ) {
        $meta_value = $wpdb->get_var( $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE order_id = %d AND meta_key = %s",
            $order_id, $meta_key
        ) );

        return $meta_value;
    } else {
    
        $order = wc_get_order( $order_id );
        if ( $order ) {
            return get_post_meta( $order->get_id(), $meta_key, true );
        } else {
            return '';
        }
    }
}
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

// Add a custom metabox
add_action( 'add_meta_boxes', 'admin_order_custom_metabox' );
function admin_order_custom_metabox() {
    $screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
        ? wc_get_page_screen_id( 'shop-order' )
        : 'shop_order';

    add_meta_box(
        'custom',
        __('Billing details','woocommerce'),
        'custom_metabox_content',
        $screen,
        'side',
        'high'
    );
}

// Metabox content
function custom_metabox_content( $object ) {
    // Get the WC_Order object
    $order = is_a( $object, 'WP_Post' ) ? wc_get_order( $object->ID ) : $object;
    $c2c_order_id = $order->get_order_number();
	   
    echo '<p>' . __('Order ID','woocommerce') . ': '. $c2c_order_id .'<p>';
      
	$upload_dir = wp_upload_dir();
	if (get_option('c2cp_settings_rftitle')) {$c2cp_settings_rftitle = get_option('c2cp_settings_rftitle');} else {$c2cp_settings_rftitle = __('Receipt no/payment serial no','c2cp-td-woocommerce');}
		echo '<p>' . $c2cp_settings_rftitle . ': </p><p><strong>' . c2c_wc_get_order_data ( $c2c_order_id, '_c2cp_cart_receipt_no') . '</strong></p>';
		echo '<p><strong>' . c2c_wc_get_order_data ( $c2c_order_id, '_c2cp_bank_account') . '</strong></p><br>';
		echo '<form action="" method=""><div class="c2c-admin-btn">';
		echo '<button class="button button-primary c2c-submit-data" name="c2c_submit_data">Save changes invoice details </button><br>';
		echo '<a href="#" class="button button-secondary" id="c2c_upload_admin_btn">Upload image</a>';

	$order_id_get = isset($_GET['page']) ? absint($_GET['id']) : 0;
	if ( c2c_wc_get_order_data ( $c2c_order_id, '_c2cp_card_filename' ) ){
		$c2cp_card_filename_churlif = c2c_wc_get_order_data ( $c2c_order_id, '_c2cp_card_filename' ) ;
		
			if (preg_match('/\b((http|https):\/\/\S+)/i', $c2cp_card_filename_churlif)) {

				$c2cp_card_filename_churl = $c2cp_card_filename_churlif;
			} else {
				$c2cp_card_filename_churl = $upload_dir['baseurl'] . '/c2cp_upload/' . $c2cp_card_filename_churlif;
			}

		
		
		echo '<a href="#" class="button button-secondary" id="c2c_remove_admin_btn" >Delete image</a>';
		
		
		echo '<div class="c2c-row">
  				<div class="c2c-column">
    			<img src="'. $c2cp_card_filename_churl .'" style="width:100%" onclick="openModal();currentSlide(1)" class="c2c-hover-shadow cursor" id="c2c_upload_admin_img">
  				</div>
			<div id="c2c_myModal" class="c2c-modal" onclick="closeModal()">
  					<span class="c2c-close cursor" onclick="closeModal()">&times;</span>
				<div class="c2c-modal-content">
				<div class="c2c-mySlides">
					<img src="'. $c2cp_card_filename_churl .'" id="c2c_upload_admin_a" style="width:100%">
				</div>
				<div class="c2c-caption-container">
					<p id="c2c_caption"></p>
				</div> 
    		</div></div> 
			</div>';

	}  
	
	echo '<input type="text" hidden id="c2c_current_order_id" name="c2c_current_order_id" value="' . $order_id_get . '">';
	echo '<input type="text" hidden id="c2c_new_url" name="c2c_new_url" value="' . $c2cp_card_filename_churl .'">';
	echo '</div></form>';
	


				// Get all enabled shipping zones
				$shipping_zones = WC_Shipping_Zones::get_zones();

				$active_shipping_methods = array();

				// Loop through each shipping zone
				foreach ($shipping_zones as $zone) {
					// Get the shipping methods for this zone
					$zone_methods = $zone['shipping_methods'];

					// Loop through each shipping method in the zone
					foreach ($zone_methods as $method_id => $method) {
						// Check if the method is enabled
						if ($method->enabled == 'yes') {
							$active_shipping_methods[] = array(
								'zone_name' => $zone['zone_name'],
								'method_title' => $method->method_title,
								'method_id' => $method_id
							);
						}
					}
				}

				// Return the list of active shipping methods
				
			
			if ( !empty($active_shipping_methods) ) { 
				echo '<select name="shipping_method">';

					foreach ($active_shipping_methods as $method) {
						echo '<option value="' . $method['method_id'] . '">' . $method['method_title'] . ' (' . $method['zone_name'] . ')</option>';
					}

				echo '</select>';
			} 

		
}


function c2cp_plugins_page_settings_link($links) { 
  $settings_link = '<a href="' . admin_url('admin.php') . '?page=wc-settings&tab=checkout&section=c2cp-settings">' . __('Setting','c2cp-td-woocommerce') . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'c2cp_plugins_page_settings_link' );




function c2cp_chek_file_exists ($bank_logo){
	$bank_logo_file = C2CP_RTD_IMAGES_URL . $bank_logo . '.png';
	$fileContents = @file_get_contents($bank_logo_file);
	

	if ( !$fileContents ) {
		$bank_logo_file = C2CP_RTD_IMAGES_URL . 'c2cp_credit.svg';
	}
	return $bank_logo_file;
}



global $pagenow;
if (isset($_POST['c2c_submit_data']) && isset($_POST['c2c_new_url']) && isset($_POST['c2c_current_order_id']) ){
	update_post_meta($_POST['c2c_current_order_id'], '_c2cp_card_filename', $_POST['c2c_new_url']);
	
	$url = esc_url(admin_url('post.php?post=' . $_POST['c2c_current_order_id'] . '&action=edit'));
	$url = html_entity_decode($url);
    header('Location: ' . $url);

    exit;
}


add_action( 'woocommerce_thankyou', 'my_custom_status_update' );



function my_custom_status_update( $order_id ) {

	$c2cp_settings_statuses = get_option('c2cp_settings_statuses');
	if (!$c2cp_settings_statuses) {$c2cp_settings_statuses = 'wc-processing';}
	
	$order = wc_get_order( $order_id );
	if ( $order->get_payment_method() === 'bacs' ) {
		$order = new WC_Order( $order_id );
		$order->update_status( $c2cp_settings_statuses );
	}

}










function c2cp_get_payment_rules_status() {


    $status = array(
        'force_bacs' => false,
        'discount'   => false,
        'type'       => '',
        'amount'     => 0,
    );


    if ( ! WC()->cart ) {
        return $status;
    }



    /*
     * محدود کردن درگاه
     */
    $enable_gateway_limit = get_option(
        'c2cp_settings_pricecheckbox'
    );


    $limit_amount = (float) get_option(
        'c2cp_settings_totalprice',
        0
    );


    $subtotal = (float) WC()->cart->get_subtotal();



    if (
        ! empty($enable_gateway_limit)
        &&
        $limit_amount > 0
        &&
        $subtotal >= $limit_amount
    ) {

        $status['force_bacs'] = true;

    }



    /*
     * تخفیف BACS
     */

    $discount_enabled = get_option(
        'c2cp_settings_discountcheckbox'
    );


    $discount_type = get_option(
        'c2cp_settings_discount_type'
    );


    $discount_amount = (float) get_option(
        'c2cp_settings_discount_amount',
        0
    );


    if (
        ! empty($discount_enabled)
        &&
        in_array(
            $discount_type,
            array(
                'percent',
                'fixed_cart'
            ),
            true
        )
        &&
        $discount_amount > 0
    ) {

        $status['discount'] = true;
        $status['type'] = $discount_type;
        $status['amount'] = $discount_amount;

    }


    return $status;

}


add_filter(
    'woocommerce_available_payment_gateways',
    'c2cp_payment_gateways_filter',
    999
);


function c2cp_payment_gateways_filter(
    $available_gateways
) {


    $rules = c2cp_get_payment_rules_status();


    if (
        ! $rules['force_bacs']
    ) {

        return $available_gateways;

    }



    foreach (
        $available_gateways as $id => $gateway
    ) {

        if (
            $id !== 'bacs'
        ) {

            unset(
                $available_gateways[$id]
            );

        }

    }


    return $available_gateways;

}



add_action(
    'woocommerce_cart_calculate_fees',
    'c2cp_payment_discount',
    999
);


function c2cp_payment_discount(
    $cart
) {


    if (
        is_admin()
        &&
        ! defined('DOING_AJAX')
    ) {
        return;
    }



    $rules = c2cp_get_payment_rules_status();



    if (
        ! $rules['discount']
    ) {
        return;
    }



    $gateway = '';


    if (
        isset($_POST['payment_method'])
    ) {

        $gateway = wc_clean(
            wp_unslash(
                $_POST['payment_method']
            )
        );

    } elseif (
        WC()->session
    ) {

        $gateway =
            WC()->session->get(
                'chosen_payment_method'
            );

    }



    if (
        $gateway !== 'bacs'
    ) {
        return;
    }



    $discount = 0;


    if (
        $rules['type'] === 'percent'
    ) {


        $discount =
            (
                $cart->get_subtotal()
                *
                $rules['amount']
            )
            /
            100;


        $label =
            'Special Discount - '
            .
            $rules['amount']
            .
            '%';


    } else {


        $discount =
            $rules['amount'];


        $label =
            'Special Discount';

    }



    if (
        $discount <= 0
    ) {
        return;
    }



    $cart->add_fee(
        $label,
        -$discount,
        false
    );


}


include(C2CP_INC . 'c2cp_card_detector.php');
