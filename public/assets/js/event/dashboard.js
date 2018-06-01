//===========================================
// jQuery document ready
//===========================================

(function($) {
	"use strict";

	var $form_confirm = $('#js-form-confirm-invitation'),
		$form_nearby = $('#js-form-view-nearby'),
		$form_confirm_host = $('#js-form-confirm-host'),
		$btn_confirm_host = $('#js-button-confirm-host')
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

	// selects event for specific date in calendar (and resets back to all)
	function select_date(target, calendar) {
		if (target === undefined || calendar === undefined) {
			return;
		}

		var $event_list = $('.event-listing .event-scroller');
		if ($(target.element).hasClass('selected')) {
			var $scroller = $('#js-event-scroller');
			var sel_date = target.date.format('MM/DD/YYYY');
			$scroller.find('.event-item').each(function () {
				var $this = $(this);
				if ($this.attr('data-event-date') != sel_date) {
					$this.remove();
				}
			});
			return;
		}

		// resets to default (which is show all for month)
		calendar.render();
	}

	// pass global to view JS
	window.clndr_select_date = select_date;

	$(function() { // document.ready

		$('#calendar').on('click', '.event-item', function(e) {
			if ($(this).attr('data-event-route')) {
				window.location.href = $(this).attr('data-event-route');
			}
		});

		$form_confirm.on('submit', function(e) {
			e.preventDefault();

			var $this = $(this);
			var data = $this.serialize();
			var route = $this.attr('action').replace('1xxx1', $this.find('#js-confirm-vendor-code').val());

			ajaxRequest(data, route, null, 'confirm-vendor');
		});

		$btn_confirm_host.on('click', function(e) {
			var $input = $('#js-confirm-host-code');
			
			if (!$input.val()) {
				return;
			}

			e.preventDefault();
			var route = $form_confirm_host.attr('action').replace('1xxx1', $form_confirm_host.find('#js-confirm-host-code').val());
			$form_confirm_host.attr('action', route);
			window.location.href = route;
		});

		$form_confirm_host.on('submit', function(e) {
			e.preventDefault();
		});

		$form_nearby.on('submit', function(e) {
			e.preventDefault();

			var $this = $(this);
			toastr.info('Feature not available');
		});

	}); // end document.ready


})(jQuery);

