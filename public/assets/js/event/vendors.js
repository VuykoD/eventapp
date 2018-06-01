// Event management
// EventApp
// version 1.0

(function($) {

	"use strict";

	function ajaxRequest(atts, ajax_url, callback) {
		if (ajax_url === undefined) {
			console.log('Missing url for AJAX request');
			return;
		}

		var def_data = {};
		var data = $.extend({}, def_data, atts);

		$.ajax({
	        url: ajax_url,
	        type: 'POST',
	        data: data,
		    dataType : 'json',
		})
		  // Code to run if the request succeeds (is done);
		  .done(function( json ) {
		  	if (!json.success) {
		  		console.log('AJAX Failed');
		  		return;
		  	}

            if (json.success && typeof callback === 'function') {
                callback(json);
            }
		  })
		  // Code to run if the request fails
		  .fail(function( xhr, status, errorThrown ) {
		    console.log('Connection failure: ' + errorThrown);
		  })
		  // Code to run regardless of success or failure;
		  .always(function( xhr, status ) {
		    // console.log(xhr);
		  });
	}

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

	function show_alert(message, location) {
		var idAlert = '.js-alert-display';
		if (location !== undefined) {
			idAlert += '-' + location; 
		}
		var $alert = $(idAlert);

		if (!message) {
			$alert.addClass('hidden');
			return;
		}

		$alert.html(message);
		$alert.removeClass('hidden');
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

	

	$(function() { // document.ready

		var $form_vendors = $('#js-form-assign-vendor'),
			$select_catg = $('#js-select-category'),
			$group_place = $('#js-group-placeholder'),
			$form_new_vendor = $('#js-form-create-vendor'),
			$modal_new_vendors = $('#modalCreateVendor'),
			$action_input = $('#js-input-action'),
			$repo_hidden,
			$group_active,
			$groups,
			active_catg
			;

		var idDeleteCatg = '.js-delete-catg-block',
			idModalAlert = '.js-alert-display-modal',
			idCatgBlock = '.js-category-block',
			idEventCriteriaBlock = 'fieldset.js-event-criteria',
			$btn_new_catg = $('#js-button-new-catg'),
			idAssignButton = '.js-button-assign',
			idInviteButton = '.js-button-invite'
			;

		var idCatgGroup = '.js-group-vendor-category',
			idHidden = 'js-repo-hidden'
			;

		$repo_hidden = $('#' + idHidden);
		if (!$repo_hidden.length) {
			$('body').append($('<div>', {class: 'hidden', id: idHidden}));
			$repo_hidden = $('#' + idHidden);
		}

		$groups = $(idCatgGroup);

		function calcProgress() {
			$form_vendors.find('.progress-bar').each(function(){
				var $this = $(this);
				var total = $this.attr('data-total');
				var free = $this.attr('data-free');
				var progress = (total != '0') ? Math.round(free / total * 100) + '%' : '0%'; 
				$this.css('width', progress);
			});
		}

		function swapGroup() {
			active_catg = $select_catg.val();
			$groups.appendTo($repo_hidden);
			$groups.filter('[data-catg-id=' + active_catg + ']').appendTo($group_place);
		}

		calcProgress();
		swapGroup();

		$select_catg.on('change', function() {
			swapGroup();
		});

		function update_vendors(data) {
			if (data.categories === undefined || !data.categories.length || data.name === undefined || data.id === undefined) {
				return;
			}

			var vendor_id = data.id,
				vendor_name = data.name,
				$blocks = $('.js-group-vendor-category'),
				idSelect = '.js-group-vendor-add select';

			data.categories.forEach(function(curValue, curIdx) {
				if (!curValue) {
					return;
				}

				var $fb = $blocks.filter('[data-catg-id=' + curValue + ']');
				if (!$fb.length) {
					return;
				} 

				$fb.find(idSelect).append($('<option>', {
					value: vendor_id, 
					text: vendor_name,
				}));
			});
		}

		// Update after AJAX
		function update_category(catg, catg_data) {
			var $catg_block = $('[data-catg-id=' + catg + ']');

			if (!$catg_block.length) {
				return;
			}

			var $h5 = $catg_block.find('h5');
			$h5.text('Free Slots: ' + catg_data.slots_free + '/' + catg_data.slots);

			var $progress = $catg_block.find('.progress-bar');
			$progress.attr('data-total', catg_data.slots);
			$progress.attr('data-free', catg_data.slots_free);

			calcProgress();

			if (catg_data.slots_free === 0) {
				$('.js-group-vendor-add').remove();
				$catg_block.append($('<p>', {text: 'Sorry, no free slots'}));
				return;
			}

			var $select = $catg_block.find('select');
			$select.find('option').remove();

			for (var idx in catg_data.vendors_avail) {
				$select.append($('<option>', {value: idx, text: catg_data.vendors_avail[idx]}));
			}

		}

		$form_vendors.on('click', idAssignButton + ',' + idInviteButton, function(e) {
			$action_input.val($(this).attr('data-action'));
		});

		// Submit ASSIGN VENDOR
		// ***************

		$form_vendors.on('submit', function(e) {
			var $this = $(this);

			e.preventDefault();

			if (!$this.valid()) {
				return;
			}

			show_alert('', 'main');
			spinner('on', 'assign-vendor');

			$.ajax({
			    url: $this.attr('data-action'),
			    data: $this.serialize(),
			    type: "POST",
			    dataType : "json",
			})
			  .done(function( json ) {
			  	if (json.success) {
				    update_category(json.catg, json.catg_data);
				    toastr.success('Operation successful');
				    return;
				}

				if (!json.success && json.exception) {
					toastr.error(json.error ? json.error : 'Internal error');
				}

				if (!json.success) {
					show_alert(json.msg, 'main');
				}

			     console.log( json );
			  })
			  .fail(function( xhr, status, errorThrown ) {
			  	show_alert('Connection failure: ' + errorThrown, 'main');
			    console.log( "Error: " + errorThrown );
			    console.log( "Status: " + status );
			    console.log( xhr );
			  })
			  .always(function( xhr, status ) {
			    spinner('off', 'assign-vendor');
			  });

			  return false;
		});

			// *** MODAL ***

		// Click NEW CATEGORY button
		// ********************

		$btn_new_catg.on('click', function (e) {
			var $parent_row = $(this).closest('.row');
			var $new_block = $parent_row.prev(idCatgBlock).clone(false);
			$new_block.find('.select2').remove();
			$new_block.find(idDeleteCatg).removeAttr('disabled');
			$new_block.find('select').val('');
			$new_block.insertBefore($parent_row).find('.use-select2').select2();

			var $crit_block = $(idEventCriteriaBlock);
			disable_duplicates($crit_block.find('select'));
			$crit_block.find('.use-select2').select2();

			$modal_new_vendors.data('bs.modal')._handleUpdate();
		});

		// Click DELETE CATEGORY button
		// ********************

		$(idEventCriteriaBlock).on('click', idDeleteCatg, function (e) {
			$(this).closest(idCatgBlock).remove();
		});

		// check for duplicate categories
		$form_new_vendor.on('change', '.select, select', function (e) {
			var $crit_block = $(idEventCriteriaBlock);
			disable_duplicates($crit_block.find('select'));
			$crit_block.find('.use-select2').select2();
		});

		// Submit NEW VENDOR
		// ***************

		$form_new_vendor.on('submit', function(e) {
			var $this = $(this),
				$modal = $this.closest('.modal'),
				$alert = $modal.find(idModalAlert);

			e.preventDefault();

			if (!$this.valid()) {
				return;
			}

			$alert.addClass('hidden');
			spinner('on', 'modal-vendor');
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
			  	if (json.success) {
			  		update_vendors(json);
				    $modal.modal('hide');
				    return;
				 }

				$alert.html('');
				$alert.removeClass('hidden');
			 	$alert.html(json.message ? json.message : 'Internal error');

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
			    spinner('off', 'modal-vendor');
			  });

			  return false;
		});

		$form_new_vendor.on('change', '.use-select2', function(e){
			$(this).select2('open');
			$(this).select2('close');
		});



	}); // end document.ready

})(jQuery);