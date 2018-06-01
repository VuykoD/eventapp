//===========================================
// 
//===========================================

(function($) {
	"use strict";

	// var $form_confirm = $('#js-form-confirm-invitation'),
	// 	$form_nearby = $('#js-form-view-nearby'),
	// 	$form_confirm_host = $('#js-form-confirm-host'),
	// 	$btn_confirm_host = $('#js-button-confirm-host')
	// 	;


	// Spinner function
	function spinner(action, location) {
		var $spinner;
		var spinner_class = '.wrap-spinner';
		if (location !== undefined) {
			spinner_class += '.js-spinner-' + location; 
		}

		$spinner = $(spinner_class);

		switch(action) {
			case('on'):
				$spinner.removeClass('hidden');
				break;

			case('off'):
				$spinner.addClass('hidden');
				break;
		}
	}

	function show_alert(message, type) {
		// type is [info, warning, success, error]
		if (type === undefined) {
			type = 'info';
		}

		toastr[type](message);
	}

	function ajaxFileRequest(data, ajax_url, callback, location) {
		if (ajax_url === undefined) {
			console.log('Missing url for AJAX request');
			return;
		}

		// var def_data = {};
		// var data = $.extend({}, def_data, atts);

		spinner('on', location);
		$.ajax({
	        url: ajax_url,
	        type: 'POST',
	        data: data,
		    // dataType : 'json',
		    enctype: 'multipart/form-data',
		    processData: false,  
		    contentType: false,
		    cache:false,
		})
		  // Code to run if the request succeeds (is done);
		  .done(function( response ) {
		  	var json;
		  	try { json = JSON.parse(response); } 
		  	catch(e) { json = response; }
		  	if (!json.success) {
		  		console.log('AJAX Failed');
		  		show_alert('[Failure] ' + (json.message || json.error), 'error');
		  		return;
		  	}

		  	if (json.success && json.message) {
		  		show_alert(json.message, 'success');
		  	}

            if (json.success && typeof callback === 'function') {
                callback(json);
            }
		  })
		  // Code to run if the request fails
		  .fail(function( xhr, status, errorThrown ) {
		    console.log('Connection failure: ' + errorThrown);
		    show_alert('Connection failure: ' + errorThrown, 'error');
		  })
		  // Code to run regardless of success or failure;
		  .always(function( xhr, status ) {
		    spinner('off', location);
		  });
	}

	

	$(function() { // document.ready

		var $form_avatar = $('#js-upload-avatar'),
			$file_input = $('#js-input-avatar'),
			$modal_cropper = $('#modalCropAvatar'),
			$cropper = $('#js-img-cropper')
		;

        function update_cropper(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                	// $cropper.show();
                    $cropper.cropper('replace', e.target.result);
                    $modal_cropper.data('bs.modal')._handleUpdate();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function refresh_page() {
        	window.location.href = window.location.href;
        }

	
	    $('#js-button-upload').on('click', function (e) {
	        $('#js-input-avatar').click();
	    });

	    $('#js-button-apply').on('click', function (e) {
	        $form_avatar.submit();
	    });

	    $file_input.on('change', function (e) {
	    	spinner('on', 'cropper');
	        var $this = $(this);
	        if (!$this.val()) {
	        	return;
	        }
	        $modal_cropper.modal('show');
	        // $cropper.hide();
	        update_cropper(this);
	        
	        spinner('off', 'cropper');
	    });

	    $form_avatar.on('submit', function (e) {
	        e.preventDefault();
	        var formData = new FormData(this);
	        var $this = $(this);
	        var cropped = $cropper.cropper('getCroppedCanvas').toDataURL('image/jpeg');
	        formData.append('avatar', cropped);
	        formData.append('filename', $file_input[0].files[0].name);

	        for (var prop in window.crop_data) {
	        	formData.append(prop, window.crop_data[prop]);
	        }

	        ajaxFileRequest(formData, $this.attr('data-action') || $this.attr('action'), refresh_page, 'cropper');
	    });

	}); // end document.ready


})(jQuery);

