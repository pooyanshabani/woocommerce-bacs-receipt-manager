jQuery(document).ready(function($) {
	var title = c2cp_admin_settingsjs.button_title;
	var confirmText = c2cp_admin_settingsjs.button_confirm;
	$('.woocommerce-save-button').after('<a href="?page=wc-settings&tab=checkout&section=c2cp-settings&action=reset_setting"  class="button button-secondary" onclick="return confirm(\'' + confirmText + '\');">' + title + '</a>');


	$('.form-table :input[id]').each(function () {

        var id = this.id;

        if (id) {
            $(this).closest('tr').addClass(id).addClass('c2cp_settings_tr');
        }

    });



  var background_uploader_c2csh;
  
  $('.c2cp-settings-url').click(function(){
    if (typeof wp !== 'undefined' && wp.media) { // بررسی وجود wp.media
      if( background_uploader_c2csh !== undefined) {
        background_uploader_c2csh.open();
        return;
      }
      
      background_uploader_c2csh = wp.media({
        title:'Select File',
        library: {
          type: 'image'
        }
      });
      
      background_uploader_c2csh.on('select', function(){
        let selected =  background_uploader_c2csh.state().get('selection');
        $('.c2cp-settings-url').val(selected.first().toJSON().url)
        var imageUrl = $(".c2cp-settings-url").val();
        $(".c2cp-settings-url_imageHolder").html(`<img src="${imageUrl}" width="auto" height="50">`);
      });
      
      background_uploader_c2csh.open();
    } else {
      //console.log('wp.media is not available.');
    }
  });

  	if(!$('#c2cp_settings_imgcheckbox').prop('checked')) {
		
		$('.c2cp_settings_iftitle').hide();
	} else {
     
      $('.c2cp_settings_iftitle').show();
    }

  $('#c2cp_settings_imgcheckbox').change(function() {
    if(this.checked) {
      
      $('.c2cp_settings_iftitle').show();
    } else {
      
      $('.c2cp_settings_iftitle').hide();
    }
  });

  if($('#c2cp_settings_pricecheckbox').prop('checked')) {
		
		$('.c2cp_settings_totalprice').show();
	} else {
      
      $('.c2cp_settings_totalprice').hide();
    }

  $('#c2cp_settings_pricecheckbox').change(function() {
    if(this.checked) {
      
      $('.c2cp_settings_totalprice').show();
    } else {
      
      $('.c2cp_settings_totalprice').hide();
    }
  });

  
  $('#c2cp_settings_page').on('change', function() {
    if ($(this).val() == 'checkout'){   
	  $('.c2cp_settings_deskcol_thp').hide();
	  $('.c2cp_settings_deskcol').show();
	 
    } else {
      
      $('.c2cp_settings_deskcol').hide();
	  $('.c2cp_settings_deskcol_thp').show();
      
    }
  });
  
  
    if ($('#c2cp_settings_page').val() == 'checkout'){
		$('.c2cp_settings_deskcol_thp').hide();
    	$('.c2cp_settings_deskcol').show();
	
    } else {
      $('.c2cp_settings_deskcol').hide();
      $('.c2cp_settings_deskcol_thp').show();
	  
    }


	if($('#c2cp_settings_discountcheckbox').prop('checked')) {
		
		$('.c2cp_settings_discount_amount').show();
		$('.c2cp_settings_discount_type').show();
	} else {
      
      	$('.c2cp_settings_discount_amount').hide();
		$('.c2cp_settings_discount_type').hide();
    }

   $('#c2cp_settings_discountcheckbox').change(function() {
		if(this.checked) {
		
			$('.c2cp_settings_discount_amount').show();
			$('.c2cp_settings_discount_type').show();
		} else {
		
			$('.c2cp_settings_discount_amount').hide();
			$('.c2cp_settings_discount_type').hide();
		}
   });

  
});