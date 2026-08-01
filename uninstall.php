<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' )) {
	exit();
}

global $wpdb;

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
