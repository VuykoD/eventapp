//===========================================
// jQuery document ready
//===========================================

(function($) {
	"use strict";

	var $form_confirm_host = $('#js-form-host-invite'),
		$check_conditions = $('#js-host-conditions'),
		$check_conditions_input = $('#js-host-hidden-conditions'),
		$text_conditions = $('#js-text-conditions'),
		$text_conditions_input = $('#js-text-hidden-conditions'),
		$group_conditions = $('#js-group-conditions'),
		$modal_commitment = $('#modalHostCommitment'),
		$wrap_conditions = $('#js-wrap-modal-conditions')
		;


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

	function ajaxRequest(atts, ajax_url, callback, location) {
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
	        data: atts,
		    dataType : 'json',
		})
		  // Code to run if the request succeeds (is done);
		  .done(function( json ) {
		  	if (!json.success) {
		  		console.log('AJAX Failed');
		  		show_alert('Confirmation failed: ' + json.message, 'error');
		  		return;
		  	}

		  	if (json.success && json.message) {
		  		show_alert(json.message, 'success');
		  	}

            if (json.success && typeof callback === 'function') {
                callback(json);
            }

            if (json.success && json.href) {
                window.location.href = json.href;
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

		$check_conditions.on('change', function(e) {
			return $(this).prop('checked') ? $group_conditions.removeClass('hidden') : $group_conditions.addClass('hidden');
		});

		$modal_commitment.on('show.bs.modal', function(e) {
			$check_conditions_input.val($check_conditions.prop('checked') ? 1 : 0);
			$check_conditions_input.prop('checked', $check_conditions.prop('checked'));
			$text_conditions_input.val($check_conditions.prop('checked') ? $text_conditions.val() : '');

			$wrap_conditions.addClass('hidden');
			if ($check_conditions.prop('checked')) {
				$wrap_conditions.removeClass('hidden');
				$wrap_conditions.find('textarea').val($text_conditions.val());
			}
		});

		$form_confirm_host.on('submit', function(e) {
			e.preventDefault();
			var $this = $(this);

			if (!$this.valid()) {
				return;
			}

			ajaxRequest($this.serialize(), $this.attr('action'), null, 'confirm-host'); 
		});

	}); // end document.ready


})(jQuery);

