// Organization management
// EventApp
// version 1.0

(function($) {

	"use strict";

	

	$(function() { // document.ready

		var $form_org = $('#js-org-create-main'),
			$spinner = $('.wrap-spinner'),
			$coord_field = $('#js-org-coord'),
			run_counter = 0
		;

		function spinner(action) {
			switch (action) {
				case 'on':
					$spinner.removeClass('hidden');
					break;

				case 'off':
					$spinner.addClass('hidden');
					break;
			}
		}

		// Submit NEW ORG
		// ****************

		$form_org.on('submit', function(e) {
			var $this = $(this);
			run_counter++;

			if ($coord_field.val() || run_counter > 2) {
				console.log('Have coordinates or whatever');
				return true;
			}

			e.preventDefault();

			spinner('on');

			// jQuery promise chain is used to query Google Geocode API
			// then submit AJAX form
			(function geo(){

				var geocoder = new google.maps.Geocoder();
				var addr = [
					$form_org.find('#address_1').val(),
					$form_org.find('#address_2').val(),
					$form_org.find('#city').val(),
					$form_org.find('#state').val(),
					$form_org.find('#zip').val()
				];
				var address = addr.join(', ');
				var deferred = $.Deferred();

				geocoder.geocode({'address': address}, function(results, status) {
			        if (status === 'OK') {
			        	$coord_field.val(JSON.stringify(results[0].geometry.location.toJSON()));
			        } 

			        console.log('Geocode returned: ' + status);
			        deferred.resolve();
		        });

				return deferred.promise();

			})().then(function(){
				$this.submit();				
			});
			  

			return false;
		});

		


	}); // end document.ready

})(jQuery);