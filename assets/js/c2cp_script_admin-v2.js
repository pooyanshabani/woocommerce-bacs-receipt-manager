jQuery(document).ready(function($) {



	var c2cp_card = c2cp_admin_settingsjs_v2.c2cp_card;
	var background_uploader_c2c_depimg;
  
  $('#c2c_upload_admin_btn').click(function(){

	  
	  
    if (typeof wp !== 'undefined' && wp.media) { 
      if( background_uploader_c2c_depimg !== undefined) {
        background_uploader_c2c_depimg.open();
        return;
      }
      
      background_uploader_c2c_depimg = wp.media({
        title:'Select File',
        library: {
          type: 'image'
        }
      });
      
      background_uploader_c2c_depimg.on('select', function(){
        let selected =  background_uploader_c2c_depimg.state().get('selection');
        $('#c2c_upload_admin_a').attr("src", selected.first().toJSON().url)
        //var imageUrl = $("#c2c_upload_admin_img").val();
        $("#c2c_upload_admin_img").attr("src", selected.first().toJSON().url);
		$("#c2c_new_url").val(selected.first().toJSON().url);
		$(".c2c-submit-data").show();
      });
      
      background_uploader_c2c_depimg.open();
    } else {
      console.log('wp.media is not available.');
    }
  });

	$('#c2c_remove_admin_btn').click(function(event){
    if (confirm('آیا از حذف تصویر مطمئن هستید!؟')) {
        $('#c2c_upload_admin_a').attr("src", c2cp_card);
        $("#c2c_upload_admin_img").attr("src", c2cp_card);
        $("#c2c_new_url").val(c2cp_card);
        $(".c2c-submit-data").show();
    } else {
        event.preventDefault();
    }
});



  });

document.addEventListener("keydown", function(event) {
  if (event.key === "Escape") {
    closeModal();
  }
});


function openModal() {
  document.getElementById("c2c_myModal").style.display = "block";
}

function closeModal() {
  document.getElementById("c2c_myModal").style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("c2c-mySlides");
  var dots = document.getElementsByClassName("c2c-demo");
  var captionText = document.getElementById("c2c_caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}