<?php 
defined('ABSPATH') || exit;

$order = wc_get_order($order_id);

   if (isset($_POST['c2cp_cart_submit_data'])) {

	   if (isset($_POST['c2cp_cart_receipt_no'])) {
		//die($order);exit;
		$receipt_no = sanitize_text_field($_POST['c2cp_cart_receipt_no']);
		$bank_details = sanitize_text_field($_POST['c2cp_bank_account']);

			if ( isset($_POST['c2cp_file_name']) ) {
				$c2cp_image_filedata = $_POST['c2cp_file_data'];
				$current_time = current_datetime();
				$c2cp_image_filename = $current_time->format('Y-m-d-H:i:s') . '-' . $_POST['c2cp_file_name'];

				// تابع تبدیل تصویر base64 به داده باینری
				function c2cp_image_base64_to_binary($image_data) {
					$pattern = '/^data:(image\/[a-zA-Z]+);base64,/';
					$replacement = '';
					$image_data = preg_replace($pattern, $replacement, $image_data);
					$image_data = str_replace(' ', '+', $image_data);			
					return base64_decode($image_data);
				}
				// استفاده از تابع برای تبدیل تصویر
				$image_data = c2cp_image_base64_to_binary($c2cp_image_filedata);

				$upload_dir = wp_upload_dir();
				$target_dir = $upload_dir['basedir'] . '/c2cp_upload/';

				// ایجاد پوشه "upload" در صورت نبودن
				wp_mkdir_p($target_dir);

				$file_path = $target_dir . $c2cp_image_filename; // تعیین مسیر فایل نهایی

				// ذخیره فایل با داده باینری
				file_put_contents($file_path, $image_data);

				// بروزرسانی متادیتاهای سفارش با اطلاعات فایل جدید
				$update_postmeta_filesuc = update_post_meta($order_id, '_c2cp_card_filename', $c2cp_image_filename);
								
			}
			
		$update_postmeta_receiptsuc = update_post_meta($order_id, '_c2cp_cart_receipt_no', $receipt_no);
		$update_postmeta_bank_account = update_post_meta($order_id, '_c2cp_bank_account', $bank_details);
			if($update_postmeta_receiptsuc) {
				wp_redirect($currentUrl);
			}
    	}

   }
