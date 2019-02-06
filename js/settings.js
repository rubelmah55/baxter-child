(function ($) {
	"use strict"; 

	// js for dooor

	function loadDoorsGallery(id, child){
		$.ajax({
			url: localized.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'load_doors',
				term_id: id,
			},
			beforeSend: function(){  
				var preloader = '<div class="preloader" style="text-align:center;transition: all 0.5s ease;margin:100px 0;"><img src="https://192.163.200.38/~bellakitchenandb/wp-content/uploads/2019/02/aug-4-2017-5-23-pm.gif" alt="loading"></div>';
				$('#load-door-images').html(preloader);
				
			},
			success: function(resp){
				if(child === undefined ){
					$(".filter-wrapper__child").html(resp.child_filter);
				}
				$("#load-door-images").html(resp.gallery);
			}

		})
	}


	// on page load 
	$(window).on("load", function(){
		loadDoorsGallery(null);
	});


	$(document).on("click", ".door-filter li", function(e){
		e.preventDefault();
		var id = $(this).data("id");
		var child = $(this).data("child");
		$(".door-filter li").removeClass("bold");
		$(this).addClass("bold");
		console.log("child", child);
		loadDoorsGallery(id, child);
	});

    

})(jQuery);