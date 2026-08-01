<?php
defined('ABSPATH') || exit;


add_action('init', 'c2cp_reset_settings_redirect');
function c2cp_reset_settings_redirect() {
//reset setting
	global $pagenow;
	if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wc-settings' && isset($_GET['tab']) && $_GET['tab'] === 'checkout' && isset($_GET['section']) && $_GET['section'] === 'c2cp-settings' && isset($_GET['action']) && $_GET['action'] == 'reset_setting'){
		delete_option('c2cp_settings_activenotif');
		delete_option('c2cp_settings_tbtoken');
		delete_option('c2cp_settings_page');
		delete_option('c2cp_settings_theme');
		delete_option('c2cp_settings_url');
		delete_option('c2cp_settings_btitle');
		delete_option('c2cp_settings_rftitle');
		delete_option('c2cp_settings_imgcheckbox');
		delete_option('c2cp_settings_iftitle');
		delete_option('c2cp_settings_tptext');
		delete_option('c2cp_settings_pricecheckbox');
		delete_option('c2cp_settings_totalprice');
		delete_option('c2cp_settings_deskcol_thp');
		delete_option('c2cp_settings_deskcol');
		delete_option('c2cp_settings_mobilecol');
		delete_option('c2cp_settings_cardnumberch');
		delete_option('c2cp_settings_fontsize');
		delete_option('c2cp_settings_statuses');
		delete_option('c2cp_settings_discountcheckbox');
		delete_option('c2cp_settings_discount_type');
		delete_option('c2cp_settings_discount_amount');

		wp_redirect(
			admin_url('admin.php?page=wc-settings&tab=checkout&section=c2cp-settings')
		);exit;
	}
}