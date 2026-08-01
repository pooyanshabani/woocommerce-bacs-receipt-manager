<?php
defined('ABSPATH') || exit;


$bank_details = get_option('woocommerce_bacs_accounts');
$c2cp_settings_cardnumberch = get_option('c2cp_settings_cardnumberch');

echo '</h4><section class="c2cp-bacs-bank-fdetails-thp">';
		foreach ($bank_details as $key => $bank) {
			$account_number = $bank['account_number'];
			$account_name = $bank['account_name'];
			$bank_name = $bank['bank_name'];
			$sort_code = $bank['sort_code'];
			$iban = $bank['iban'];
			$bank_logo = substr($account_number, 0, 6);
			$bank_logo_file = C2CP_RTD_IMAGES_URL . $bank_logo . '.png';

			echo '<div class="c2cp-bacs-bank-titel"  data-key="' . $key . '" onclick="c2cviewBankDetail(this)"><img src="' . $bank_logo_file . '"><h5 class="wc-bacs-bank-details-account-name">  ' . $bank_name . '</h5></div><ul class="c2cp-bacs-bank-details c2cp-bacs-bank-details-' . $key . '" data-key="' . $key . '">';
			if ($bank_name) {echo '<li class="bank_name"><strong>' . $bank_name . ' - ' . $account_name . '</strong></li>';}
			if ($account_number && $c2cp_settings_cardnumberch === 'no' ) {echo '<li class="account_number"><span class="account_number_title">' . __( 'Account number', 'woocommerce' ) . ':  </span><strong>' . $account_number . '</strong><span class="c2cp-bacs-bank-copy" onclick="copyText(this)"><img src="'. C2CP_RTD_IMAGES_URL .'c2cp_copy.svg"></span></li>';}
			if ($sort_code){echo '<li class="sort_code"><span class="account_number_title">' . __( 'Sort code', 'woocommerce' ) . ':  </span><strong>' . $sort_code . '</strong><span class="c2cp-bacs-bank-copy" onclick="copyText(this)"><img src="'. C2CP_RTD_IMAGES_URL .'c2cp_copy.svg"></span></li>';}
			if ($iban){echo '<li class="iban"><span class="account_number_title">' . __( 'IBAN', 'woocommerce' ) . ':  </span><strong>' . $iban .'</strong><span class="c2cp-bacs-bank-copy" onclick="copyText(this)"><img src="'. C2CP_RTD_IMAGES_URL .'c2cp_copy.svg"></span></li>';}
			 echo '</ul>';
		}
  echo '</section>';