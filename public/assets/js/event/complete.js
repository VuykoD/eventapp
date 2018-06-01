// Event management
// EventApp
// version 1.0

(function($) {

	"use strict";


	window.formPreview = function($target) {
		var $form = $('#js-form-complete-main');
		var $elems = $form.find('input, select, textarea, .js-vendor-feedback-block').not('[type=submit], [type=hidden], [type=search]');
		var $table = $('<table>', {class: 'table table-striped table-hover'});
		var $label, th_label, td_text, $tr, $sel_opts;

		function $vendorBlock($block) {
			var name = $block.find('h2.form-section').text();
			var rating = $block.find('[name$="[rating]"]').val();
			//var attendance = $block.find('[name$="[attendance]"] option:selected').text();
			//var comments = $block.find('[name$="[comments]"]').val();

			$tr = $('<tr>');
			$('<th>', {text: name}).appendTo($tr);
			$('<td>', {text: rating}).appendTo($tr);

			return $tr;
		}


		$elems.each(function(index) {
			var $this = $(this);

			if ($this.hasClass('js-vendor-feedback-block')) {
				$vendorBlock($this).appendTo($table);
				return true;
			}

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

				case 'textarea':
					td_text = $('<div>', {html: $this.val()}).text();

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

		var $vendor_fb = $(".js-vendor-feedback-block"),
			$btn_next = $('#js-button-next-vendor'),
			$btn_prev = $('#js-button-prev-vendor')
			;

		$vendor_fb.not('[data-block-id=1]').addClass('hidden');

		$btn_next.on('click', function(e) {
			var $this = $(this);
			var block_id = parseInt($this.attr('data-block-id'), 10);
			var max_id = parseInt($this.attr('data-max-id'), 10);
			block_id = isNaN(block_id) ? 0 : block_id;
			max_id = isNaN(max_id) ? 0 : max_id;

			if (!block_id || block_id > max_id) {
				return;
			}

			$vendor_fb.addClass('hidden');
			$vendor_fb.filter('[data-block-id=' + block_id + ']').removeClass('hidden');
			$btn_prev.attr('data-block-id', block_id - 1);
			$this.attr('data-block-id', block_id + 1);
		});

		$btn_prev.on('click', function(e) {
			var $this = $(this);
			var block_id = parseInt($this.attr('data-block-id'), 10);
			var max_id = parseInt($this.attr('data-max-id'), 10);

			if (!block_id || block_id > max_id) {
				return;
			}

			$vendor_fb.addClass('hidden');
			$vendor_fb.filter('[data-block-id=' + block_id + ']').removeClass('hidden');
			$btn_next.attr('data-block-id', block_id + 1);
			$this.attr('data-block-id', block_id - 1);
		});


		


	}); // end document.ready

})(jQuery);