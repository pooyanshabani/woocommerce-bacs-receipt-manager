<?php
defined('ABSPATH') || exit;

add_action('woocommerce_thankyou', 'c2cp_display_data_thankyoupage', 10, 1);

function c2cp_display_data_thankyoupage($order_id) {
	global $wpdb;
	if (get_option('c2cp_settings_tptext')) {$c2cp_settings_tptext = get_option('c2cp_settings_tptext');} else {$c2cp_settings_tptext = __('After checking the financial affairs, your order will be sent.','c2cp-td-woocommerce');}

	if (get_option('c2cp_settings_rftitle')) {$c2cp_settings_rftitle = get_option('c2cp_settings_rftitle');} else {$c2cp_settings_rftitle = __('Receipt no/payment serial no','c2cp-td-woocommerce');}
	if (get_option('c2cp_settings_btitle')) {$c2cp_settings_btitle = get_option('c2cp_settings_btitle');} else {$c2cp_settings_btitle = __('Register payment details','c2cp-td-woocommerce');}
	if (get_option('c2cp_settings_imgcheckbox')) {$c2cp_settings_imgcheckbox = get_option('c2cp_settings_imgcheckbox');} else {$c2cp_settings_imgcheckbox = 'yes';}
	$order = wc_get_order($order_id);
	$currentUrl = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $payment_method = $order->get_payment_method();
    if ($payment_method === 'bacs') {
		$upload_dir = wp_upload_dir();
		if ( c2c_wc_get_order_data ( $order_id, '_c2cp_cart_receipt_no' ) ) {
		?>
		<div class="c2cp-display-thankyoupage">
			<div>
				<h4><?php echo $c2cp_settings_rftitle . ':'; ?></h4>
				<p><strong><?php echo c2c_wc_get_order_data ( $order_id, '_c2cp_cart_receipt_no' );?></strong></p>
				<br>
				<p class="c2cp-display-thankyoupage-notice">⚠  <?php echo $c2cp_settings_tptext; ?></p>
			</div>
			<div>
			<?php	
		if ( c2c_wc_get_order_data ( $order_id, '_c2cp_card_filename' ) && $c2cp_settings_imgcheckbox == 'yes') { ?>
			
				<img style="max-width:100%" src="<?php echo $upload_dir['baseurl'] . '/c2cp_upload/' .  c2c_wc_get_order_data ( $order_id, '_c2cp_card_filename' );?>">
			<?php } ?>
			</div>
		</div>	
		<?php
		} else {		
			$bacs_settings = get_option('woocommerce_bacs_settings');
			include(C2CP_INC . 'c2cp_thpform.php');
			include(C2CP_INC . 'c2cp_thpform_submit.php');
			echo '<br><h5>' . $bacs_settings["description"] . '</h5><br>';
			include(C2CP_INC . 'c2cp_thankyou_bank.php');
		  }
	}
}