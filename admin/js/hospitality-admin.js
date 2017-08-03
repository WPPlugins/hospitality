jQuery(document).ready( function($) {

	// Admin notice dismiss event handler
	$("#hsp-admin-notice-dismiss").click( function() {
		$('#hsp-admin-notice').remove();

		$.ajax({
			url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
			data:{
				'action':'hospitality_ajax',
				'fn':'dismiss_upgrade_message'
			},
			dataType: 'JSON',
			success:function(data){
				if ( data.errorData != null && data.errorData == 'true' ) {
					reportError( data );
				}

				var returnCode = data;
				if ( returnCode == false ) {
					reportError('dismiss_upgrade_notice AJAX call returned false');
				}

			},
			error: function(errorThrown){
				console.log(errorThrown);
			}

		});
	});

	$("#hsp-setup-notice-dismiss").click( function() {
		$('#hsp-setup-notice').remove();

		$.ajax({
			url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
			data:{
				'action':'hospitality_ajax',
				'fn':'dismiss_setup_message'
			},
			dataType: 'JSON',
			success:function(data){
				if ( data.errorData != null && data.errorData == 'true' ) {
					reportError( data );
				}

				var returnCode = data;
				if ( returnCode == false ) {
					reportError('dismiss_setup_notice AJAX call returned false');
				}

			},
			error: function(errorThrown){
				console.log(errorThrown);
			}

		});
	});


	$('.gst-sortable').sortable();
	$('.gst-sortable').disableSelection();




	/* This function makes an ajax call to retrieve js configs for post edit input fields and then sets them */
	setPostEditOptions();


	if ( hsp_admin_objectl10n.current_post_type == 'pricing-models') {
		// displays gaps and overlaps in coverage
		displayCoverageErrors();
		$('.gst_date_input').datepicker({ dateFormat: 'M dd' });

	}
	else {
		$('.gst_date_input').datepicker();
	}

	// Room location availability checking
	if ( hsp_admin_objectl10n.current_post_type == 'reservations') {

		$('#duration, #room_location_id, #start_time').change(function () {
			var duration = $('#duration').val();
			var room_location_id = $('#room_location_id').val();
			var start_time = $('#start_time').val();

			// Make sure date is set to midnight and add checkin time.
			var d = new Date($('#start_time').val());
			var dateOfArrivalLong = new Date( d.toDateString() ).getTime();

			var hoursFromMidnight = 0;
			if ( hsp_admin_objectl10n.checkInTime.endsWith('PM')) {
				hoursFromMidnight = 12;
			}

			var timeParts = hsp_admin_objectl10n.checkInTime.split(':');
			var hours = parseInt( timeParts[0] );
			var minuteParts = timeParts[1].split(' ');
			var minutes = parseInt( minuteParts[0] );

			hoursFromMidnight +=  hours;
			hoursFromMidnight += minutes / 60 ;

			dateOfArrivalLong += hoursFromMidnight * 3600000 ;
			
			var lengthOfStayLong = duration * 86400000 ;

			var searchCriteria = {
				'room_location_id' : room_location_id,
				'dateOfArrivalLong' : dateOfArrivalLong,
				'lengthOfStayLong' : lengthOfStayLong
			};



			$.ajax({
				url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
				data:{
					'action':'hospitality_ajax',
					'fn':'get_room_location_availability',
					'searchCriteria' : JSON.stringify( searchCriteria )
				},
				dataType: 'JSON',
				success:function(data){
					if ( data.errorData != null && data.errorData == 'true' ) {
						reportError( data );
					}
					else {
						if ( data.is_available == false ) {
							$('#gst-room-location-message').empty();
							$('#gst-room-location-message').prepend('<span class="gst-admin-error-message">' + hsp_admin_objectl10n.reservation_conflict  +' </span>')
						}
						else {
							$('#gst-room-location-message').empty();
						}
					}
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}

			});


		});


	}


	$('.gst_time_input').timepicker({ 'timeFormat': 'h:i A' });

	$('.gst_pricing_input').on('change', function() {
		$( this).attr("value", $(this).val() );
	});

	$( '#meta_pricing_model_list' ).on( "sortupdate", function( event, ui ) {
		reInitPriceModelList( event, $(this) );
	});



	$('.gst-sort-edit-delete').click( function( e ) {

		if ( $( this ).hasClass('gst-room-location-delete') ) {
			deleteRoomLocation( $(this).data('rlid'), $( this ).parent().parent());
		}
        else {
            $( this ).parent().parent().remove();
        }
	});

	$('#meta_pricing_model_list .gst-sort-edit-delete').click( function( e) {
		$(this).parent().parent().remove();
		reInitPriceModelList( e, $(this) );
	});


	$('#post').submit( function() {

	});

    $('#publish').click( function( e ) {

        $('.gst_room_location_unit_number_input').each( function( idx, elem ) {
            if ( $(this).val() == '') {
                alert('Room location unit numbers cannot be blank.');
                e.preventDefault();
                $(this).focus();
            }
        });
    }) ;

	$('.gst_date_input').change( function() {
		if ( hsp_hsp_admin_objectl10n.current_post_type = 'pricing-models') {
			displayCoverageErrors();
		}
	});



	var meta_image_frame;

	$('.gst-sort-edit-image-upload').click(function(e){
		handleImageUpload( e, $(this) );
	});

	$('.gst-sort-edit-image-add').click( function( e ) {


		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );

		// connect event hanlders to new list item.
		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-delete').click( function() {
			$( this ).parent().parent().remove();
		});

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-image-upload').click( function() {
			handleImageUpload( e, $(this) );
		});

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst_clear_input_target').val('');
		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-image-upload').trigger('click');

	});


	$('.gst-sort-edit-add').click( function() {


		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('input').attr('value','');

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-delete').click( function() {
			$( this ).parent().parent().remove();
		});
	});

	$('.gst-sort-edit-room-location-add').click( function() {


		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('input').attr('value','');

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-delete').click( function() {
			$( this ).parent().parent().remove();
		});
        bindRoomLocationInputEvents();
	});

	$('.gst-sort-edit-pricing-add').click( function( e ) {

		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );
		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('input').attr('value','');
		reInitPriceModelList( e, $(this));

	});



	$(".option-tree-setting-body").append('<a class="ot_setting_body_done">Done</a>');
	
	getAmenityList( $("#meta_room_amenity_select").val() );
	getPricingModel( $("#meta_room_pricing_select").val() );
	
	$('#meta_room_amenity_select').change( function() {	
			getAmenityList( this.value );		
	});
	
	$('#meta_room_pricing_select').change( function() {	
		getPricingModel( this.value );	
	});
	
	$(".ot_setting_body_done").click( function() {
		$('.option-tree-setting-body[style="display: block;"]').css('display','none');
	});
	
	$('.option-tree-list-item-add').click( function() {
		// console.log('got it');
		setTimeout( function() {
			 $('.option-tree-setting-body[style="display: block;"]').append('<a class="ot_setting_body_done">Done</a>');
			 $('.option-tree-setting-body[style="display: block;"] .ot_setting_body_done').click( function() {
					$('.option-tree-setting-body[style="display: block;"]').css('display','none');
				});
		}, 500);
	});

	$('.gst_room_location_unit_number_input').change( function() {

	});

	bindRoomLocationInputEvents();

    function bindRoomLocationInputEvents() {
        $('.gst_room_location_unit_number_input').focusout( function() {
            var unitNumber = $( this ).val();
            var locationID = $( this).siblings('.gst_room_location_location_id_select').val();
            var roomLocationID = $( this ).siblings('[name^="meta_room_locations-id"]').val();
            $( this ).data('validation-pending', 'true');
            validateRoomLocation( roomLocationID, locationID, unitNumber );

            // $('.hsp-unit-number-msg').html( unitNumber );
        });
    }



	function setPostEditOptions() {

		$.ajax({
			url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
			data:{
				'action':'hospitality_ajax',
				'fn':'get_post_edit_options'
			},
			dataType: 'JSON',
			success:function(data){
				if ( data.errorData != null && data.errorData == 'true' ) {
					reportError( data );
				}

				// Set options here.
				var postEditOptions = data;
				$('.gst_countable').simplyCountable( {
					counter:            '#gst_counter',
					countType:          'characters',
					maxCount:           postEditOptions.room_excerpt_max_char_count,
					strictMax:          false,
					overClass:			'gst-excerpt-over',
					countDirection:     'down'
				});

			},
			error: function(errorThrown){
				console.log(errorThrown);
			}

		});




	}


	/*
	 * Get locations and display in select.
	 */



	function displayLocationsOptions() {


		var targetElem = '.gst_room_location_location_id_select';
		var locations = hsp_admin_objectl10n.locations ;
		for ( i in locations ) {
			$( targetElem ).each( function() {
				$(this).append( '<option value="' +  locations[i].id + '">' + locations[i].title  + '</option>');

			});
		}
	}

	/** 
	 * Get amenity list functions. 
	 */
	function getAmenityList( postID ) {
		
	
		if ( postID != null && postID != "" ) {
			$.ajax({
				url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
				data:{
					'action':'hospitality_ajax',
					'fn':'get_amenity_set_list',
					'postID' : postID
				},
				dataType: 'JSON',
				success:function(data){
					if ( data.errorData != null && data.errorData == 'true' ) {
						reportError( data );
					}
					displayAmenityList( data );
			    },
				error: function(errorThrown){
					console.log(errorThrown);
			    }
			
			});
		}
		else {
			displayAmenityList("");
		}
	}
	
	function displayAmenityList( amenitySetList ) {
	
		var targetElem = ".adm_amenity_set_list";
		var containerElem = "#amenity_list_panel";
		
		$( targetElem ).empty();
		
		if ( amenitySetList == "") {
			$( targetElem ).append('<p>' + hsp_admin_objectl10n.note_no_amnenity_set_selected + '</p>');
			return ;
		}
		
		$( targetElem ).append('<div id="' +  containerElem +  '">');	
		$( targetElem ).append('<ul>');
		for ( i in amenitySetList ) {
			$( targetElem ).append( '<li>' + amenitySetList[i] + '</li>');
		}
		$( targetElem ).append('</ul>');
		$( targetElem ).append('</div>');
		
	}
	
	/**
	 * Get pricing model functions. 
	 */
	
	function getPricingModel( postID ) {
			
		if ( postID != null && postID != "" ) {
			$.ajax({
				url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
				data:{
					'action':'hospitality_ajax',
					'fn':'get_pricing_model',
					'postID' : postID
				},
				dataType: 'JSON',
				success:function(data){
					if ( data.errorData != null && data.errorData == 'true' ) {
						reportError( data );
					}
					displayPricingModel( data );
			    },
				error: function(errorThrown){
					alert( hsp_admin_objectl10n.get_pricing_model_error + errorThrown.responseText.substring(0,500) );
					console.log(errorThrown);
			    }
			
			});
		}
		else {
			displayPricingModel("");
		}
	}

	function displayPricingModel( pricingModel ) {
		var targetElem = ".adm_pricing_model";
		var containerElem = "pricing_model_panel";
		
		
		$( targetElem ).empty();
		
		if ( pricingModel == "") {
			$( targetElem ).append('<p>' + hsp_admin_objectl10n.note_no_pricing_model_selected + '</p>');
			return ;
		}
		
		
		var htmlSrc = '<div id="' +  containerElem +  '">' ;
		htmlSrc += '<ul id="pricing_model">';
		for ( i in pricingModel ) {
			htmlSrc += '<li><h3>' + pricingModel[i].title + '</h3>';
			if ( pricingModel[i].dateRange1 != "") 
				 htmlSrc += "<h4>" + pricingModel[i].dateRange1 + "</h4>" ;



			// Build DOW pricing string

			priceStr = '<table><tr>';
			priceStr += '<th>' + hsp_admin_objectl10n.sunday + '</th>';
			priceStr += '<th>' + hsp_admin_objectl10n.monday + '</th>';
			priceStr += '<th>' + hsp_admin_objectl10n.tuesday + '</th>';
			priceStr += '<th>' + hsp_admin_objectl10n.wednesday + '</th>';
			priceStr += '<th>' + hsp_admin_objectl10n.thursday + '</th>';
			priceStr += '<th>' + hsp_admin_objectl10n.friday + '</th>';
			priceStr += '<th>' + hsp_admin_objectl10n.saturday + '</th>';

			priceStr += '</tr><tr>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.sunday + '</td>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.monday + '</td>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.tuesday + '</td>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.wednesday + '</td>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.thursday + '</td>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.friday + '</td>';
			priceStr += '<td>' + pricingModel[i].priceByDOW.saturday + '</td>';

			priceStr += '</tr>';


			priceStr += '</table>'

			htmlSrc += '<div class="hsp_room_price">' + priceStr + '</div></li>';
			
		}
		htmlSrc += '</ul>';
		htmlSrc += '</div>';
		
		$( targetElem ).append( htmlSrc );
		
		
		
	}

    $('#gen-demo').click( function() {
        genDemoData();
    });
	
	function genDemoData() {
        
        $.ajax({
            url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
            data:{
                'action':'hospitality_ajax',
                'fn':'gen_demo_data',
            },
            dataType: 'JSON',
            success:function(data){
                if ( data.errorData != null && data.errorData == 'true' ) {
                    reportError( data );
                }
                alert('Gen Demo Data Success' );
            },
            error: function(errorThrown){
                alert( hsp_admin_objectl10n.get_pricing_model_error + errorThrown.responseText.substring(0,500) );
                console.log(errorThrown);
            }

        });
    }



	function handleImageUpload( e, elem ) {

		e.preventDefault();


		// tag fields that will be updated by the media metabox.
		elem.siblings('.gst_image_url_target').addClass('gst-set-media-target');
		elem.siblings('a').children('img').addClass('gst-set-media-thumbnail-target');


		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			// title: meta_image.title,
			// button: { text:  meta_image.button },
			library: { type: 'image' }
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function(){


			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$('.gst-set-media-target').val(media_attachment.url);
			$('.gst-set-media-target').removeClass('gst-set-media-target');
			$('.gst-set-media-thumbnail-target').attr( "src", media_attachment.url );
			$('.gst-set-media-thumbnail-target').removeClass('gst-set-media-thumbnail-target');


		});

		// Opens the media library frame.
		// wp.media.editor.open();
		meta_image_frame.open();
	}

	/*
	* Called from Room meta box when user clicks the delete button.
	* TODO: Needs confirmation.
	 */
	function deleteRoomLocation( postID, roomLocationElem ) {

        if ( ! confirm('Delete room location?')) {
            return ;
        }

        if ( postID != null && postID != "" ) {
            $.ajax({
                url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
                data:{
                    'action':'hospitality_ajax',
                    'fn':'delete_room_location',
                    'postID' : postID
                },
                dataType: 'JSON',
                success:function(data){
                    if ( data.errorData != null && data.errorData == 'true' ) {
                        displayError( '.hsp-room-location-msg', data );
                    }
                    else {
                        $( roomLocationElem ).remove();
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }

            });
        }

	}

    function validateRoomLocation( roomLocationID, locationID, roomNumber  ) {
        

        $.ajax({
            url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
            data:{
                'action':'hospitality_ajax',
                'fn':'validate_room_location',
                'roomLocationID' : roomLocationID,
                'locationID' : locationID,
                'roomNumber' : roomNumber
            },
            dataType: 'JSON',
            success:function(data){
                if ( data.errorData != null && data.errorData == 'true' ) {
                    reportError( data );
                }
                else {
                    displayRoomLocationValidation( data );
                }
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }

        });
    }

    

    function displayRoomLocationValidation( validation ) {

        if ( validation.isValid == true ) {
            $('.gst_room_location_unit_number_input').each( function( idx, elem ) {

                if ( $(this).data('validation-pending') == 'true') {
                    $( this ).siblings('.hsp-unit-number-msg').html('') ;
                    $(this).data('validation-pending', 'false');

                }
            });

            return ;
        }
        else {
            $('.gst_room_location_unit_number_input').each( function( idx, elem ) {

                if ( $(this).data('validation-pending') == 'true') {
                    $( this ).siblings('.hsp-unit-number-msg').html( validation.message ) ;
                    $(this).data('validation-pending', 'false');

                }
            });
        }
    }


    function reInitPriceModelList ( e, elem ) {

		$('#meta_pricing_model_list').children().each( function( idx, elem ) {
			var liHTML = $( elem ).html();
			var replaceStr = 'meta_pricing_model_list[' + idx + ']' ;
			var modliHTML = liHTML.replace(/meta_pricing_model_list\[[0-9]*\]/g, replaceStr );
			modliHTML = '<li class="ui-state-default">' +  modliHTML + '</li>';
			$( this).replaceWith( modliHTML);
		});

		$('.gst_date_input').removeClass('hasDatepicker');
		$('.gst_date_input').attr('id', '');
		$('.gst_date_input').datepicker({ dateFormat: 'M dd' });

		$('.gst_pricing_input').on('change', function() {
			$(this).attr("value", $(this).val() );
		});

		$('.gst_date_input').change( function() {
			displayCoverageErrors();
		});

		$('#meta_pricing_model_list .gst-sort-edit-delete').click( function() {
			$(this).parent().parent().remove();
			reInitPriceModelList( e, $(this) );
		});


	}

	function displayCoverageErrors() {

		var date_inputs = $('.gst_date_input');

		coveredRanges = new Array();
		var date_list = "";
		for (var i = 0; i < date_inputs.length; i = i + 2) {
			date_list = date_list + ' ' + date_inputs[i].value + ' ' + date_inputs[i + 1].value;
			startStr = date_inputs[i].value;
			endStr = date_inputs[i + 1].value;
			now = new Date();
			startDate = new Date(startStr + ', ' + now.getFullYear() );
			endDate = new Date(endStr  + ', ' + now.getFullYear() );


			startLongTime = Date.UTC(now.getFullYear(), startDate.getMonth(), startDate.getDate());
			endLongTime = Date.UTC(now.getFullYear(), endDate.getMonth(), endDate.getDate());
			janOneLongTime = Date.UTC(now.getFullYear(), 0, 1);
			startDOY = ( startLongTime - janOneLongTime ) / 86400000;
			endDOY = ( endLongTime - janOneLongTime ) / 86400000;

			// condition occurs when adding a new pricing model
			if (date_inputs.length == 2 && ( isNaN(startDOY) || isNaN(endDOY) )) {
				startDOY = 0;
				endDOY = 365;
			}

			if (endDOY < startDOY) {
				range1 = {
					min: 0,
					max: endDOY
				};
				range2 = {
					min: startDOY,
					max: 365
				}
				coveredRanges.push(range1);
				coveredRanges.push(range2);

			}
			else {
				range1 = {
					min: startDOY,
					max: endDOY
				}
				coveredRanges.push(range1);
			}

		}

		coveredRanges.sort(function (a, b) {
			return a.min - b.min;
		});

		overlaps = new Array();
		gaps = new Array();

		if (coveredRanges.length == 1) {
			if ( coveredRanges[0].min > 0 ) {
				gaps.push({ min: 1, max: coveredRanges[0].min  });
			}
			if (coveredRanges[0].max < 364 ) {
				gaps.push({ min: coveredRanges[0].max + 2, max: 365 });
			}

		}
		else {
			for (i = 0; i < coveredRanges.length; i++) {

				thisIdx = i;
				nextIdx = ( i + 1 == coveredRanges.length ? 0 : i + 1 );

				if (nextIdx == 0) {
					rangeCmp = coveredRanges[i].max - 364;
					if (rangeCmp == 0 || rangeCmp == 1) {
						// OK
					}
					else {
						gaps.push({min: coveredRanges[i].max + 2, max: 365});
					}
				}
				else {
					rangeCmp = coveredRanges[nextIdx].min - coveredRanges[i].max;

					if (i == 0) {
						if (coveredRanges[i].min == 0) {
							// OK
						}
						else {
							gaps.push({min: 1, max: coveredRanges[i].min});
						}
					}

					if (rangeCmp == 1 || rangeCmp == -364) {
						// OK
					}
					else if (rangeCmp == 0) {
						// overlap, one day
						overlaps.push({min: coveredRanges[i].max + 1, max: coveredRanges[i].max + 1});
					}
					else if (rangeCmp > 1) {
						// gap
						gaps.push({min: coveredRanges[i].max + 2, max: coveredRanges[nextIdx].min});
					}
					else if (rangeCmp < 0) {
						// overlap
						overlaps.push({min: coveredRanges[nextIdx].min + 1, max: coveredRanges[i].max + 1});
					}
				}
			}
		}
		// Convert overlaps and gaps to message sting
		var gapsMessage = hsp_admin_objectl10n.gaps_msg + " ";

		for ( i = 0; i < gaps.length ; i++ ){
			// to ms from start of year
			min_lt = gaps[i].min * 86400000 + janOneLongTime ;
			max_lt = gaps[i].max * 86400000 + janOneLongTime ;
			minDate = new Date( min_lt );
			maxDate = new Date( max_lt );
			gapsMessage += monthToString( minDate.getMonth() ) + ' ' + minDate.getDate() + '-';
			gapsMessage += monthToString( maxDate.getMonth() ) + ' ' + maxDate.getDate() + '  '

		}

		var overlapsMessage = hsp_admin_objectl10n.overlaps_msg + " ";

		for ( i = 0; i < overlaps.length ; i++ ){
			// to ms from start of year
			min_lt = overlaps[i].min * 86400000 + janOneLongTime ;
			max_lt = overlaps[i].max * 86400000 + janOneLongTime ;
			minDate = new Date( min_lt );
			maxDate = new Date( max_lt );
			overlapsMessage += monthToString( minDate.getMonth() ) + ' ' + minDate.getDate() + '-';
			overlapsMessage += monthToString( maxDate.getMonth() ) + ' ' + maxDate.getDate() + '  '

		}

		if ( overlaps.length > 0 || gaps.length > 0 ) {
			$('.meta_pricing_model_list_feedback').html('<p>' + overlapsMessage + '</br>' + gapsMessage +'</p>');
		}
		else {
			$('.meta_pricing_model_list_feedback').empty();
		}


	}

	// TODO: localization
	function monthToString( monthNum ) {

		switch (monthNum) {
			case 0:
				return hsp_admin_objectl10n.jan;
			case 1:
				return hsp_admin_objectl10n.feb;
			case 2:
				return hsp_admin_objectl10n.mar;
			case 3:
				return hsp_admin_objectl10n.apr;
			case 4:
				return hsp_admin_objectl10n.may;
			case 5:
				return hsp_admin_objectl10n.jun;
			case 6:
				return hsp_admin_objectl10n.jul;
			case 7:
				return hsp_admin_objectl10n.aug;
			case 8:
				return hsp_admin_objectl10n.sep;
			case 9:
				return hsp_admin_objectl10n.oct;
			case 10:
				return hsp_admin_objectl10n.nov;
			case 11:
				return hsp_admin_objectl10n.dec ;
			default:
				console.log("Unexpected month number value" + monthNum );
		}
		return hsp_admin_objectl10n.jan;
	}




	function testFunction( e, elem ) {
		alert('test function');
	}

	function reportError ( error ) {
		console.log ( error );
	}

    function displayError ( selector, error ) {
        $( selector ).html( error.errorMessage );
    }

    function clearError ( selector ) {
        $( selector ).html('');
    }
	
});

var HSP_DECIMAL_PLACES = 7;

function initAdminMap() {

    // Geocode feature

    var geocoder = new google.maps.Geocoder();

    jQuery('.hsp-geocode-button').click( function( e ) {

        e.preventDefault();

        var street = jQuery('#hsp-settings-street-1').val();
        var city = jQuery('#hsp-settings-city').val();
        var state = jQuery('#hsp-settings-state').val();
        var postal_code =  jQuery('#hsp-settings-postal-code').val();
        var country = jQuery('#hsp-settings-country').val();

        var address = street + ', ' + city + ', ' + state + ' ' + postal_code ;

        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {

                var lat = results[0].geometry.location.lat();
                var lng = results[0].geometry.location.lng();

                lat  = parseFloat( lat.toFixed( HSP_DECIMAL_PLACES ));
                lng = parseFloat( lng.toFixed( HSP_DECIMAL_PLACES ));

                jQuery('#hsp-settings-lat').val( lat );
                jQuery('#hsp-settings-lng').val( lng );
                jQuery('#hsp-settings-geocode-message').html('<p>' +  hsp_admin_objectl10n.geocode_ok   +  '</p>');

            } else {
                jQuery('#hsp-settings-geocode-message').html('<p>' + hsp_admin_objectl10n.geocode_error  + status + '</p>');
            }
        });



    });



}