jQuery(document).ready(function($) {
	
	if ( ! objectl10n.sliderOn ) {
		return ;
	}

	
	// ajax call for slider configuration.
	jQuery.ajax({
		url:  objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
		data:{
			'action':'hospitality_ajax',
			'fn':'get_slider_config',
			'documentURL' : document.URL,
			'postID' : objectl10n.postID,
			'sliderPostIDs' : objectl10n.sliderPostIDs
		},
		dataType: 'JSON',
		success:function(data){
			if ( data.errorData != null && data.errorData == 'true' ) {
				reportError( data );
				return;
			}
			configSliders( data );
	    },
		error: function(errorThrown){
			alert( objectl10n.js_slider_config_error + errorThrown.responseText.substring(0,500) );
			console.log(errorThrown);
	    }
	
	});



	function configSliders( config_array ) {
		for ( var i = 0 ; i < config_array.length ; i++ ) {
			configSlider( config_array[i] )
		}
	}

	function configSlider( config ) {


		jQuery('#guestaba_slider-' + config.post_id + ' .slider_item').attr({
			"height" : config.height + "px",
			"width" : config.width + "px"
			});

		jQuery('#guestaba_slider-' + config.post_id + ' .hsp_slide img').css("height", config.height + "px");


		var fadeEffect = false ;
		if ( config.effect == 'fade' ) {
			var fadeEffect = true ;
		} 
		
		jQuery('#guestaba_slider-' + config.post_id + ' .hsp_slider').slick( {
			autoplay: true,
			adaptiveHeight: false,
			pauseOnHover: false,
			variableWidth: false,
			fade: fadeEffect,
			autoplaySpeed: config.slide_duration,
			speed: config.animation_duration
		});
	
		
	}

	function configSliderOld( config ) {


		jQuery('.slider_item').attr({
			"height" : config.height + "px",
			"width" : config.width + "px"
		});

		jQuery('.hsp_slide img').css("height", config.height + "px");


		var fadeEffect = false ;
		if ( config.effect == 'fade' ) {
			var fadeEffect = true ;
		}

		jQuery('.hsp_slider').slick( {
			autoplay: true,
			adaptiveHeight: false,
			pauseOnHover: false,
			variableWidth: false,
			fade: fadeEffect,
			autoplaySpeed: config.slide_duration,
			speed: config.animation_duration
		});


	}



	function reportError( errorData ) {
		var errorString = objectl10n.server_error + errorData.errorMessage ;
		alert( errorString );
	}
});




