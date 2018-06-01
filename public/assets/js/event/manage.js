// Event management
// EventApp
// version 1.0

(function($) {

	"use strict";

	window.initMap = function() {
		// console.log('Google API ready');
	};

	window.formPreview = function($target) {
		var $form = $('#js-form-create-main');
		var $elems = $form.find('input, select').not('[type=submit], [type=hidden], [type=search]');
		var $table = $('<table>', {class: 'table table-striped table-hover'});
		var $label, th_label, td_text, $tr, $sel_opts;
		$elems.each(function(index) {
			var $this = $(this);
			$tr = $('<tr>');

			$label = $this.siblings('label');			
			th_label = $label.length ? $label.text().replace('Select ', '') : '';
			$('<th>', {text: th_label}).appendTo($tr);

			switch($this.prop('tagName').toLowerCase()) {
				case 'select':
					$sel_opts = $this.find('option:selected');
					td_text = $sel_opts.text();

					if ($sel_opts.length > 1) {
						var opts = [];
						$sel_opts.each(function(idx){
							opts.push($(this).text());
						});
						td_text = opts.join(', ');
					}

					break;

				default:
					td_text = $this.val();
					
					break;
			}
			$('<td>', {text: td_text}).appendTo($tr);

			$tr.appendTo($table);
		});

		$target.html('');
		$table.appendTo($target);
	};

	$(function() { // document.ready

		var $select_host = $("#js-select-hosts"),		
			$form_host = $('#js-form-create-host'),
			$select_venue = $("#js-select-venues"),		
			$form_venue = $('#js-form-create-venue'),
			$hidden_repo = $('#js-repo-hidden-org'),
			$form_org = $('#js-form-create-org'),
			$select_org = $('#js-select-orgs'),	
			$btn_create_org = $('#js-button-create-org'),
			$btn_close_org = $('#js-close-create-org'),
			$btn_new_catg = $('#js-button-new-catg')
			;

		var idAlert = '.js-alert-display',
			idSpinner = '.wrap-spinner',
			idHeader = '.modal-header',
			idVenueCoord = '#js-venue-coord',
			idDeleteCatg = '.js-delete-catg-block',
			idCatgBlock = '.js-category-block',
			idEventCriteriaBlock = 'fieldset.js-event-criteria',
			idReview = '#js-block-review-event'
			;

		var $host_modal = $form_host.closest('.modal');
		var $host_modal_content = $host_modal.find('.modal-content');

		// init select2 in modal popup
		$('.use-select2-modal').select2({
	        dropdownParent: $host_modal_content,
	    });

	    function updateTimedroppers(period) {
	    	if (period === undefined) {
	    		period = 60;
	    	}

	    	var $start_time = $('#start_time'),
	    		$end_time = $('#end_time'),

	    		fmt_time = 'h:mm a',

	    		$mt_start = moment($start_time.val(), fmt_time),
	    		$mt_end = moment($end_time.val(), fmt_time),

	    		$st_hours = $($start_time.attr('data-td-wrap')).find('.td-time span:first'),
	    		$st_mins = $($start_time.attr('data-td-wrap')).find('.td-time span:last'),
	    		$end_hours = $($end_time.attr('data-td-wrap')).find('.td-time span:first'),
	    		$end_mins = $($end_time.attr('data-td-wrap')).find('.td-time span:last')
	    		;

	    		var $mt_v = moment($mt_end);
	    		if ($mt_v.subtract(period, 'minutes').isAfter($mt_start)) {
	    			return false;
	    		}

				$mt_end = moment($mt_start).add(period, 'minutes');
				$end_time.val($mt_end.format(fmt_time));
				$end_hours.text($mt_end.format('hh'));		
				$end_hours.attr('data-id', $mt_end.format('HH'));		
				$end_mins.text($mt_end.format('mm'));		
				$end_mins.attr('data-id', $mt_end.format('mm'));

				return true;		
	    }

	    updateTimedroppers(60);

	    $('#start_time').on('change', function(e) {
	    	updateTimedroppers(60);
	    });

	    $('#end_time').on('change', function(e) {
	    	updateTimedroppers(0);
	    });

	    function remove_new_line($select) {
    		$select.find('option[value=new]').remove();
	    }

	    $select_host.on('change', function(e) {
	    	var $this = $(this);

	    	if ($this.val() == 'new') {
	    		$('#modalCreateHost').modal('show');
	    		$this.val('').change();
	    	}
	    });

	    $select_venue.on('change', function(e) {
	    	var $this = $(this);

	    	if ($this.val() == 'new') {
	    		$('#modalCreateVenue').modal('show');
	    		$this.val('').change();
	    	}
	    });

		function swapForms($tohide, $toshow) {
			var form_title = $toshow.attr('data-form-title');
			$tohide.slideUp();
			$tohide.promise().done(function(){
				$tohide.appendTo($hidden_repo);
				$toshow.addClass('hidden');
				$toshow.appendTo($host_modal_content);
				if (form_title) {
					$host_modal.find(idHeader).text(form_title);
				}
				$toshow.slideDown();
				$toshow.removeClass('hidden');
			});
			clearErrors();
		}

		function clearErrors() {
			$(idAlert).html('').addClass('hidden');
		}

		function disable_duplicates($target) {
			var values = [];
			$target.each(function(index, element) {
				values[index] = $(this).val();
			});

			$target.find('option').prop('disabled', false);

			$target.each(function(index, element) {
				values.forEach(function(curValue, curIdx) {
				    if ((curIdx != index) && curValue) {
				    	$(element).find('option[value=' + curValue + ']').prop('disabled', true);
				    }
				});
			});
		}

		// Submit NEW HOST
		// ***************

		$form_host.on('submit', function(e) {
			var $this = $(this),
				$modal = $this.closest('.modal'),
				$alert = $modal.find(idAlert),
				$spinner = $modal.find(idSpinner);

			e.preventDefault();

			$alert.addClass('hidden');
			$spinner.removeClass('hidden');
			// Using the core $.ajax() method
			$.ajax({
			 
			    // The URL for the request
			    url: $this.attr('data-action'),
			 
			    // The data to send (will be converted to a query string)
			    data: $this.serialize(),
			 
			    // Whether this is a POST or GET request
			    type: "POST",
			 
			    // The type of data we expect back
			    dataType : "json",
			})
			  // Code to run if the request succeeds (is done);
			  // The response is passed to the function
			  .done(function( json ) {
			  	var error_msg;

			  	if (json.id) {
				    $modal.modal('hide');
				    $select_host.append($('<option>', {value: json.id, text: json.name, selected: ''}));
				    remove_new_line($select_host);
				    $select_host.change();
				    return;
				 }

				$alert.html('');
				$alert.removeClass('hidden');
				if (json.msg || json.error) {
					error_msg = json.msg || json.error;
					if (error_msg.indexOf("Column 'email' cannot be null") !== -1) {
						error_msg = "Email is required";
					}
					if (error_msg.indexOf("Integrity constraint violation: 1062 Duplicate entry") !== -1) {
						error_msg = "Email address is already in use";
					}
				 	$alert.html(error_msg);
				}

			     console.log( json );
			  })
			  // Code to run if the request fails; the raw request and
			  // status codes are passed to the function
			  .fail(function( xhr, status, errorThrown ) {
			    // alert( "Sorry, there was a problem!" );
			    $alert.removeClass('hidden');
			    $alert.html('Connection failure: ' + errorThrown);
			    console.log( "Error: " + errorThrown );
			    console.log( "Status: " + status );
			    console.log( xhr );
			  })
			  // Code to run regardless of success or failure;
			  .always(function( xhr, status ) {
			    $spinner.addClass('hidden');
			  });

			  return false;
		});

		// Submit NEW VENUE
		// ****************

		$form_venue.on('submit', function(e) {
			var $this = $(this),
				$modal = $this.closest('.modal'),
				$alert = $modal.find(idAlert),
				$spinner = $modal.find(idSpinner),
				$coord_field = $modal.find('#js-venue-coord');

			e.preventDefault();

			$alert.addClass('hidden');
			$spinner.removeClass('hidden');

			// jQuery promise chain is used to query Google Geocode API
			// then submit AJAX form
			(function geo(){

				var geocoder = new google.maps.Geocoder();
				var addr = [
					$form_venue.find('#address_1').val(),
					$form_venue.find('#address_2').val(),
					$form_venue.find('#city').val(),
					$form_venue.find('#state').val(),
					$form_venue.find('#zip').val()
				];
				var address = addr.join(', ');
				var deferred = $.Deferred();

				geocoder.geocode({'address': address}, function(results, status) {
			        if (status === 'OK') {
			        	$(idVenueCoord).val(JSON.stringify(results[0].geometry.location.toJSON()));
			        } 

			        console.log('Geocode returned: ' + status);
			        deferred.resolve();
		        });

				return deferred.promise();

			})().then(function(){
				// Using the core $.ajax() method
				return $.ajax({
				 
				    // The URL for the request
				    url: $this.attr('data-action'),
				 
				    // The data to send (will be converted to a query string)
				    data: $this.serialize(),
				 
				    // Whether this is a POST or GET request
				    type: "POST",
				 
				    // The type of data we expect back
				    dataType : "json",
				});
			})
			  // Code to run if the request succeeds (is done);
			  // The response is passed to the function
			  .done(function( json ) {
			  	if (json.id) {
				    $modal.modal('hide');
				    $select_venue.append($('<option>', {value: json.id, text: json.name, selected: ''}));
				    remove_new_line($select_venue);
				    $select_venue.change();
				    return;
				 }

				$alert.html('');
				$alert.removeClass('hidden');
				if (json.msg) {
				 	$alert.html(json.msg);
				}

			     console.log( json );
			  })
			  // Code to run if the request fails; the raw request and
			  // status codes are passed to the function
			  .fail(function( xhr, status, errorThrown ) {
			    // alert( "Sorry, there was a problem!" );
			    $alert.removeClass('hidden');
			    $alert.html('Connection failure: ' + errorThrown);
			    console.log( "Error: " + errorThrown );
			    console.log( "Status: " + status );
			    console.log( xhr );
			  })
			  // Code to run regardless of success or failure;
			  .always(function( xhr, status ) {
			    $spinner.addClass('hidden');
			  });

			  return false;
		});

		// Submit NEW ORG
		// **************

		$form_org.on('submit', function(e) {
			var $this = $(this),
				$modal = $this.closest('.modal'),
				$alert = $modal.find(idAlert),
				$spinner = $modal.find(idSpinner);

			e.preventDefault();

			$alert.addClass('hidden');
			$spinner.removeClass('hidden');
			// Using the core $.ajax() method
			$.ajax({
			 
			    // The URL for the request
			    url: $this.attr('data-action'),
			 
			    // The data to send (will be converted to a query string)
			    data: $this.serialize(),
			 
			    // Whether this is a POST or GET request
			    type: "POST",
			 
			    // The type of data we expect back
			    dataType : "json",
			})
			  // Code to run if the request succeeds (is done);
			  // The response is passed to the function
			  .done(function( json ) {
			  	if (json.id) {
				    swapForms($form_org, $form_host);
				    $select_org.append($('<option>', {value: json.id, text: json.name, selected: ''}));
				    $select_org.change();
				    return;
				 }

				$alert.html('');
				$alert.removeClass('hidden');
				if (json.msg) {
				 	$alert.html(json.msg);
				}

			     console.log( json );
			  })
			  // Code to run if the request fails; the raw request and
			  // status codes are passed to the function
			  .fail(function( xhr, status, errorThrown ) {
			    // alert( "Sorry, there was a problem!" );
			    $alert.removeClass('hidden');
			    $alert.html('Connection failure: ' + errorThrown);
			    console.log( "Error: " + errorThrown );
			    console.log( "Status: " + status );
			    console.log( xhr );
			  })
			  // Code to run regardless of success or failure;
			  .always(function( xhr, status ) {
			    $spinner.addClass('hidden');
			  });

			  return false;
		});

		// on show/hide host modal check that new org form is hidden
		// *********************************************************

		$host_modal.on('hidden.bs.modal show.bs.modal', function (e) {
			if (!$form_host.parents().filter($hidden_repo).length) {
				return;
			}

			if ($form_host.attr('data-form-title')) {
				$host_modal.find(idHeader).text($form_host.attr('data-form-title'));
			}
			$(this).find(idAlert).addClass('hidden').html('');
			$form_host.css('display', '').removeClass('hidden').appendTo($host_modal_content);
			$form_org.appendTo($hidden_repo);
		});

		// Click NEW ORG button
		// ********************

		$btn_create_org.on('click', function (e) {
			swapForms($form_host, $form_org);
		});

		// Click CANCEL NEW ORG button
		// ********************

		$btn_close_org.on('click', function (e) {
			e.preventDefault();
			swapForms($form_org, $form_host);
			clearErrors();
		});

		// Click NEW CATEGORY button
		// ********************

		$btn_new_catg.on('click', function (e) {
			var $parent_row = $(this).closest('.row'),
				$new_block = $parent_row.prev(idCatgBlock).clone(false),
				$crit_block = $(idEventCriteriaBlock),
				ctr_row, new_name, new_slots_name, idx,
				$select, $input;

			ctr_row = $crit_block.attr('data-row-ctr') || $crit_block.find('.row').length;
			if (!ctr_row) {
				ctr_row = 1;
			}

			$select = $new_block.find('select');
			$input = $new_block.find('input[type=text]');

			new_name = $select.attr('name');
			new_slots_name = $input.attr('name');

			idx = new_name.lastIndexOf('[');
			if (idx > 0) {
				new_name = new_name.slice(0, idx) + '[' + ctr_row + ']';
				$select.attr('name', new_name).attr('id', new_name);
 			}

 			idx = new_slots_name.lastIndexOf('[');
			if (idx > 0) {
				new_slots_name = new_slots_name.slice(0, idx) + '[' + ctr_row + ']';
				$input.attr('name', new_slots_name).attr('id', new_slots_name);
 			}

 			ctr_row++;
 			$crit_block.attr('data-row-ctr', ctr_row);
			
			$select.val('');
			$input.val('');
			$new_block.find('.select2').remove();
			$new_block.find(idDeleteCatg).removeAttr('disabled');
			$new_block.insertBefore($parent_row);

			disable_duplicates($crit_block.find('select'));
			$crit_block.find('.use-select2').select2();
		});

		$(idEventCriteriaBlock).on('change', '.select, select', function (e) {
			var $crit_block = $(idEventCriteriaBlock);
			disable_duplicates($crit_block.find('select'));
			$crit_block.find('.use-select2').select2();
		});

		$(idEventCriteriaBlock).find('select').change();

		// Click DELETE CATEGORY button
		// ********************

		$(idEventCriteriaBlock).on('click', idDeleteCatg, function (e) {
			$(this).closest(idCatgBlock).remove();
		});



	}); // end document.ready

})(jQuery);