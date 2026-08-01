function c2cviewBankDetail(element) {
	var dataKey = jQuery(element).data('key');
    jQuery(element).next('ul.c2cp-bacs-bank-details').slideToggle();
    jQuery('ul.c2cp-bacs-bank-details').not(jQuery(element).next('ul.c2cp-bacs-bank-details')).slideUp();
	jQuery('#c2cp_bank_account option').each(function() {
        if (jQuery(this).data('key') == dataKey) {
                
			jQuery(this).prop('selected', true);
        }
    });
}

jQuery(document).ready(function($) {
	
  $("input#c2cp_cart_imgdoc").change(function(e) {
    let imagedoc = e.target.files[0];
		if (imagedoc) { // بررسی وجود فایل
		let imagedoc_reader = new FileReader();
		imagedoc_reader.readAsDataURL(imagedoc);
		let filename = imagedoc.name;
		imagedoc_reader.onload = function() {
			$("img#c2cp_cart_image").attr('src', imagedoc_reader.result).attr('srcset', imagedoc_reader.result);
			$("input#c2cp_file_data").val(imagedoc_reader.result);
			$("input#c2cp_file_name").val(filename);
			$("#c2cp_cart_close_image").css('display','flex');
		}
		}
	});
	var defcrs = $("img#c2cp_cart_image").attr('defcrs');
		$("#c2cp_cart_close_image").click(function(e) {
			$("img#c2cp_cart_image").attr('src', defcrs).attr('srcset', defcrs);
			$("#c2cp_cart_close_image").hide();
			$("input#c2cp_file_data").val('');
			$("input#c2cp_file_name").val('');
			$("input#c2cp_cart_imgdoc").val('');
		});	


    $('#c2cp_bank_account').change(function() {
        // دریافت مقدار data-key گزینه انتخاب‌شده
        var selectedKey = $(this).find(':selected').data('key');
        
        // همه‌ی عناصر با کلاس "c2cp-bacs-bank-titel" را مخفی کن
        $('.c2cp-bacs-bank-details').hide();
        
        // عنصری که data-key برابر با گزینه انتخاب‌شده دارد، نمایش بده
        $('.c2cp-bacs-bank-details[data-key="' + selectedKey + '"]').css('display', 'block');
    });


	$(document.body).on('change', 'input[name="payment_method"]', function () {

        $('body').trigger('update_checkout');

    });



});




function copyText(element) {
    var textToCopy = element.previousElementSibling.textContent.trim();
	var textToA = element.previousElementSibling.textContent;
    var copyAlert = textToA + " کپی شد";
    var tempInput = document.createElement('input');
    document.body.appendChild(tempInput);
    tempInput.value = textToCopy;
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    alert(copyAlert);
}



