<?php
defined('ABSPATH') || exit;

global $wpdb;
if (get_option('c2cp_settings_tptext')) {$c2cp_settings_tptext = get_option('c2cp_settings_tptext');} else {$c2cp_settings_tptext = __('After checking the financial affairs, your order will be sent.','c2cp-td-woocommerce');}
if (get_option('c2cp_settings_iftitle')) {$c2cp_settings_iftitle = get_option('c2cp_settings_iftitle');} else {$c2cp_settings_iftitle = __( 'Receipt image', 'c2cp-td-woocommerce' );}
if (get_option('c2cp_settings_imgcheckbox')) {$c2cp_settings_imgcheckbox = get_option('c2cp_settings_imgcheckbox');} else {$c2cp_settings_imgcheckbox = 'yes';}
if (get_option('c2cp_settings_rftitle')) {$c2cp_settings_rftitle = get_option('c2cp_settings_rftitle');} else {$c2cp_settings_rftitle = __('Receipt no/payment serial no','c2cp-td-woocommerce');}
if (get_option('c2cp_settings_btitle')) {$c2cp_settings_btitle = get_option('c2cp_settings_btitle');} else {$c2cp_settings_btitle = __('Register payment details','c2cp-td-woocommerce');}
if (get_option('c2cp_settings_url')) {$c2cp_settings_url = get_option('c2cp_settings_url');} else {$c2cp_settings_url = C2CP_RTD_IMAGES_URL . 'c2cp_card.png' ;}
if (get_option('c2cp_settings_theme')) {$c2cp_settings_theme = get_option('c2cp_settings_theme');} else {$c2cp_settings_theme = 'red';}
if (get_option('c2cp_settings_page')) {$c2cp_settings_page = get_option('c2cp_settings_page');} else {$c2cp_settings_page = 'checkout';}
?> 

	<form action="" method="post">
		<div id="c2cp_card_checkout_fields" class="c2cp-card-checkout-fields payment_box payment_method_bacs <?php echo'c2cp-checkout-' . $c2cp_settings_theme . '-color'; ?>"> 
			<div class="c2cp-card-fields-container">
				<h3><?php echo $c2cp_settings_btitle; ?></h3>
				<p class="form-row form-row-wide validate-required validatereceipt_no" id="c2cp_cart_receipt_no" data-priority="120"><label for="c2cp_cart_receipt_no" class=""><?php echo $c2cp_settings_rftitle . ': '; ?><abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="c2cp_cart_receipt_no" id="c2cp_cart_receipt_no" required></span></p>
			<?php 
			if ($c2cp_settings_imgcheckbox == 'yes') { ?>
				<input type="hidden" name="c2cp_file_data" id="c2cp_file_data" hidden/>
				<input type="hidden" name="c2cp_file_name" id="c2cp_file_name" hidden/>
				<label for="c2cp_cart_imgdoc" class=""><?php echo $c2cp_settings_iftitle . ': '; ?></label>
				<input type="file" name="c2cp_cart_imgdoc" id="c2cp_cart_imgdoc" accept="image/png, image/gif, image/jpeg"/><br><br>
				<?php 
			}
			$bank_details = get_option('woocommerce_bacs_accounts');
				if ( $bank_details ) {

					echo  '<label for="c2cp_cart_receipt_no" class="">';
					echo __('Bank','woocommerce') . ': ';
					echo '</label><span class="woocommerce-input-wrapper">';

					echo '<select name="c2cp_bank_account" id="c2cp_bank_account">';
					foreach ($bank_details as $key => $bank) {
						$account_name = $bank['account_name'];
						$bank_name = $bank['bank_name'];
						echo '<option value="'. $bank_name .' - ' . $account_name . '" data-key="' . $key . '"> '. $bank_name .' - ' . $account_name . ' </option>';
						
					}
					echo '</select></span></p>';
				
				
			
			}
				if ($c2cp_settings_page != 'checkout') { ?>
				<button class="single_add_to_cart_button button" name="c2cp_cart_submit_data"><?php _e( 'Submit', 'woocommerce' ); ?></button>
				<?php } ?>
				
			</div>
			<div class="c2cp-card-img-container">
				<span id="c2cp_cart_close_image" >x</span>	
				<img id="c2cp_cart_image" src="<?php echo $c2cp_settings_url; ?>" defcrs="<?php echo $c2cp_settings_url; ?> ">
			</div>
		</div>
	</form>
   <?php




	