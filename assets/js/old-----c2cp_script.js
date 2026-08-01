
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