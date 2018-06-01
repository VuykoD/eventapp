$(document).ready(function() {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$('.vendor-registration').on('submit', function(e) {
		e.preventDefault();
	});
	$('.is-org').find('input').on('change', function(e) {
		if ($(this).val() == 'yes') {
			$('.code-input').slideDown();
			$('#code').trigger('change');
			$('#screen-1').find('button').data('step', 3);
			$('#screen-3').find('button.backward').data('step', 1);
		} else {
			$('.code-input').slideUp();
			$('#screen-1').find('button').removeClass('disabled');
			$('#screen-1').find('button').data('step', 2);
			$('#screen-3').find('button.backward').data('step', 2);
		}
	});
	$('#code').on('input change paste keyup', function(e) {
		if ($(this).val().length == 5) {
			$('#screen-1').find('button').removeClass('disabled');
		} else {
			$('#screen-1').find('button').addClass('disabled');
		}
	});
	$('button[data-step]').on('click', function(e) {
		e.preventDefault();
		if ($(this).hasClass('disabled')) return;
		var currentScreen = $('.form-screen').filter(function() {
			return this.style.display != 'none';
		});
		if (currentScreen.attr('id') == 'screen-1' && $('.is-org').find('input[name="is_org"]:checked').val() == 'yes') {
			checkOrgCode($('#code').val(), $(this).data('url'), 3);
			return;
		}
		if (currentScreen.attr('id') == 'screen-2' && $(this).data('step') == 3) {
			var error = false;
			$('#screen-2').find('input[data-mandatory]').each(function(index, item) {
				if ($(item).val() == '') {
					$(item).attr('placeholder', $(item).data('mandatory'));
					$(item).addClass('highlighted');
					error = true;
				}
			});
			if (error) return;
		}
		changeScreen($(this).data('step'));
	});
	$('.vendor-registration').find('[data-mandatory]').on('input paste change keyup', function(e) {
		$(this).attr('placeholder', '');
		$(this).removeClass('highlighted');
	});
	$('#add-category').on('click', function(e) {
		e.preventDefault();
		var select = $('#cats-list :selected');
		if (select.attr('disabled') == 'disabled') return;
		select.attr('disabled', 'true');
		$('#empty-cats-error').hide();
		var span = $('<span class="selected-cat" data-id="' + select.val() + '">' + select.text() + '<i class="icon-circle-cross remove-cat"></i></span>');
		$('#selected-cats').append(span);
		span.find('i').on('click', function(e) {
			console.log($('#cats-list').find('option[value="' + $(this).siblings('span').data('id') + '"]'));
			$('#cats-list').find('option[value="' + $(this).parent().data('id') + '"]').prop('disabled', false);
			$(this).parent().remove();
		});
	});
	$('.vendor-registration').find('button[type="submit"]').on('click', function(e) {
		e.preventDefault();
		var error = false;
		$('#screen-3').find('[data-mandatory]').each(function(index, item) {
			if ($(item).val() == '') {
				$(item).attr('placeholder', $(item).data('mandatory'));
				$(item).addClass('highlighted');
				error = true;
			}
		});
		if ($('#selected-cats').find('span').length == 0) {
			error = true;
			$('#empty-cats-error').fadeIn();
		}
		if (error) return;
		var selectedCategories = [];
		$('#selected-cats').find('.selected-cat').each(function(index, item) {
			// console.log($(item));
			selectedCategories.push($(item).data('id'));
		});

		// console.log(selectedCategories);
		var data = {
			persona: {
				first_name: $('#user-name').val(),
				last_name: $('#user-lastname').val(),
				select: 3,
				class: "App\\Models\\Event\\Person\\EventVendor",
				title: $('#user-title').val(),
				phone: $('#user-phone').val(),
				alt_phone: $('#user-altphone').val(),
				about_us: $('#user-aboutus').val(),
			},
			name: $('#user-name').val(),//to pass RegisterRequest validation
			email: $('#user-email').val(),
			category: {id: selectedCategories},
			password: $('#user-password').val(),
			password_confirmation: $('#user-passconfirm').val(),
			status: 0,
			confirmed: 0,
			assignees_roles: {3: 3},
			confirmation_email: 1,
			organization: false
		};
		
		$('#error').fadeOut();
		spinner('on');
		if ($('.is-org').find('input[name="is_org"]:checked').val() == 'yes') {
			data.org_id = $('#code').val();
			register(data);
		} else {
			data.organization = {
				name: $('#org-name').val(),
				address_1: $('#org-addr1').val(),
				address_2: $('#org-addr2').val(),
				city: $('#org-city').val(),
				state: $('#org-state').val(),
				zip: $('#org-zip').val(),
				phone: $('#org-phone').val(),
				website: $('#org-website').val(),
				vendor_only: 1
			};

			// jQuery promise chain is used to query Google Geocode API
			// then submit AJAX form
			(function geo(){

				var geocoder = new google.maps.Geocoder();
				console.log(geocoder);
				var addr = [
					data.organization.name,
					data.organization.address_1,
					data.organization.address_2,
					data.organization.city,
					data.organization.state,
					data.organization.zip
				];
				var address = addr.join(', ');
				var deferred = $.Deferred();

				console.log(address);

				geocoder.geocode({'address': address}, function(results, status) {
			        if (status === 'OK') {
			        	data.organization.lat_lng = JSON.stringify(results[0].geometry.location.toJSON());
			        } 

			        console.log('Geocode returned: ' + status);
			        deferred.resolve();
		        });

				return deferred.promise();

			})().then(function(){
				register(data);
			});
		}
	});
	function changeScreen(step) {
		$('.form-screen').hide();
		$('#screen-' + step).fadeIn();
		if (step != 1 && step != 4) {
			$('.flexbox-container > div').removeClass('col-md-4').removeClass('offset-md-4').addClass('col-md-10').addClass('col-md-offset-1');
			$('.card-subtitle').css('margin', '0');
		} else {
			$('.flexbox-container > div').addClass('col-md-4').addClass('offset-md-4').removeClass('col-md-10').removeClass('col-md-offset-1');
			$('.card-subtitle').css('margin', '10px 0 20px');
		}
	}
	function register(data) {
		$.ajax({
			method: 'POST',
			url: $('.vendor-registration').attr('action'),
			data: data,
			success: function(response) {
				spinner('off');
				console.log(response);
				$('#screen-4').find('p').html(response.message);
				changeScreen(4);
			},
			error: function(response) {
				spinner('off');
				console.log(response);
				$('#error').html(response.responseJSON[Object.keys(response.responseJSON)[0]]).fadeIn();
			}
		});
	}
	function checkOrgCode(code, url, step) {
		spinner('on');
		$('#org-error').fadeOut();
		$.ajax({
			method: 'POST',
			url: url, 
			data: {
				code: code
			},
			success: function(response) {
				spinner('off');
				console.log(response);
				changeScreen(step);
			},
			error: function(response) {
				spinner('off');
				console.log(response);
				$('#org-error').html(response.responseJSON.message).fadeIn();
			}
		});
	}
	function spinner(action) {
		var $spinner = $('.wrap-spinner');
		switch (action) {
			case 'on':
				$spinner.removeClass('hidden');
				break;

			case 'off':
				$spinner.addClass('hidden');
				break;
		}
	}
});

	