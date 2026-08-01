<?php
defined('ABSPATH') || exit;

add_action('woocommerce_review_order_before_payment', 'c2cp_card_checkout_fields');
function c2cp_card_checkout_fields( $checkout ) {
	$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
    if (isset($available_gateways['bacs'])) {
	global $wpdb;
	$c2cp_theme = get_option('c2cp_settings_theme');
   include(C2CP_INC . 'c2cp_thpform.php');
   
   }
}

add_action('woocommerce_checkout_process', 'c2cp_validate_receipt_field');

function c2cp_validate_receipt_field() {
	if (get_option('c2cp_settings_rftitle')) {$c2cp_settings_rftitle = get_option('c2cp_settings_rftitle');} else {$c2cp_settings_rftitle = __('Receipt no/payment serial no','c2cp-td-woocommerce');}
  if ($_POST['payment_method'] === 'bacs') {
    if (empty($_POST['c2cp_cart_receipt_no'])) {
      wc_add_notice ('<strong>' . sprintf( __('%s is a required field.', 'woocommerce'), $c2cp_settings_rftitle) . '</strong>', 'error');
    }
  }
}




add_action('woocommerce_checkout_create_order', 'c2cp_save_checkout_fields_data');

function c2cp_save_checkout_fields_data($order) {
    if (isset($_POST['c2cp_cart_receipt_no'])) {
        $order->update_meta_data('_c2cp_cart_receipt_no', sanitize_text_field($_POST['c2cp_cart_receipt_no']));
    }

    if (isset($_POST['c2cp_file_name']) && $_POST['c2cp_file_name']) {
        $c2cp_image_filedata = $_POST['c2cp_file_data'];
		$current_time = current_datetime();
        $c2cp_image_filename = $current_time->format('Y-m-d-H:i:s') . '-' . $_POST['c2cp_file_name'];

		
		function c2cp_image_base64_to_binary($image_data) {
			$pattern = '/^data:(image\/[a-zA-Z]+);base64,/';
			$replacement = '';
			$image_data = preg_replace($pattern, $replacement, $image_data);
			$image_data = str_replace(' ', '+', $image_data);			
			return base64_decode($image_data);
		}
		
		$image_data = c2cp_image_base64_to_binary($c2cp_image_filedata);

        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['basedir'] . '/c2cp_upload/';

        
        wp_mkdir_p($target_dir);

        $file_path = $target_dir . $c2cp_image_filename; 

        
        file_put_contents($file_path, $image_data);

       
        $order->update_meta_data('_c2cp_card_filename', $c2cp_image_filename);
    }
}

add_action( 'wp_enqueue_scripts', 'c2cp_wpdocs_styles_method' );
	function c2cp_wpdocs_styles_method() {
		$custom_css = 'section.woocommerce-bacs-bank-details {display: none;}';
		wp_add_inline_style( 'c2cp-view-style', 'section.woocommerce-bacs-bank-details {display: none;}' );
	}

	include(C2CP_INC . 'c2cp_thankyou.php');



add_filter( 'woocommerce_gateway_description', 'gateway_bacs_custom_fields', 20, 2 );
function gateway_bacs_custom_fields( $description, $payment_id ){
	$c2cp_settings_cardnumberch = get_option('c2cp_settings_cardnumberch');
	$bank_details = get_option('woocommerce_bacs_accounts');
	if( 'bacs' === $payment_id && $bank_details){
		ob_start(); 
	echo '<section class="c2cp-bacs-bank-fdetails">';
		foreach ($bank_details as $key => $bank) {
			$account_number = $bank['account_number'];
			$account_name = $bank['account_name'];
			$bank_name = $bank['bank_name'];
			$sort_code = $bank['sort_code'];
			$iban = $bank['iban'];

			
			$card = c2cp_detect_card( $account_number );


			

			
				$bank_logo_file = esc_url( C2CP_RTD_IMAGES_URL . $card['logo'] );

				
		

			

			echo '<div class="c2cp-bacs-bank-titel" data-key="' . $key . '" onclick="c2cviewBankDetail(this)"><img src="' . $bank_logo_file . '"><h5 class="wc-bacs-bank-details-account-name" >  ' . $bank_name . '</h5></div><ul class="c2cp-bacs-bank-details c2cp-bacs-bank-details-' . $key . '" data-key="' . $key . '">';
			if ($bank_name) {echo '<li class="bank_name"><strong>' . $bank_name . ' - ' . $account_name . '</strong></li>';}
			if ($account_number && $c2cp_settings_cardnumberch === 'no' ) {echo '<li class="account_number"><span class="account_number_title">' . __( 'Account number', 'woocommerce' ) . ':  </span><strong>' . $account_number . '</strong><span class="c2cp-bacs-bank-copy" onclick="copyText(this)"><img src="'. C2CP_RTD_IMAGES_URL .'c2cp_copy.svg"></span></li>';}
			if ($sort_code){echo '<li class="sort_code"><span class="account_number_title">' . __( 'Sort code', 'woocommerce' ) . ':  </span><strong>' . $sort_code . '</strong><span class="c2cp-bacs-bank-copy" onclick="copyText(this)"><img src="'. C2CP_RTD_IMAGES_URL .'c2cp_copy.svg"></span></li>';}
			if ($iban){echo '<li class="iban"><span class="account_number_title">' . __( 'IBAN', 'woocommerce' ) . ':  </span><strong>' . $iban .'</strong><span class="c2cp-bacs-bank-copy" onclick="copyText(this)"><img src="'. C2CP_RTD_IMAGES_URL .'c2cp_copy.svg"></span></li>';}
			 echo '</ul>';
		}
  echo '</section>';
  $description .= ob_get_clean(); 

}
    return $description;
}



