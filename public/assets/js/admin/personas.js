//===========================================
// Personas edit/create pages
//===========================================

// Main functional class/namespace
if (typeof evdata === 'undefined') {
    evdata = {};
}

//===========================================
// jQuery document ready
//===========================================

(function($) {
	"use strict";

	var idEdit			= 'js-persona-edit',
		idNew			= 'js-persona-new-',
		idSelect		= 'js-persona-select',
		idHidden		= 'js-persona-repo',
		idRoleCheckbox	= 'role-',

		$ps_edit,
		$ps_select,
		$repo_hidden,
		$px_new,
		$px_roles_chk;


	$(function() { // document.ready

		var idDeleteCatg = '.js-delete-catg-block',
			idCatgBlock = '.js-category-block',
			idEventCriteriaBlock = 'fieldset.js-event-criteria',
			$btn_new_catg = $('#js-button-new-catg'),
			$form_create_user = $('#js-form-create-user'),
			$checkbox_employer = $('#js-host-employer')
			;

		$repo_hidden = $('#' + idHidden);
		if (!$repo_hidden.length) {
			$('body').append('<div class="hidden" id="' + idHidden + '"></div>');
			$repo_hidden = $('#' + idHidden);
		}

		$ps_edit = $('#' + idEdit);
		$ps_select = $('#' + idSelect);
		$px_new = $('[id^=' + idNew + ']');
		$px_roles_chk = $('[id^=' + idRoleCheckbox + ']');

		if ($ps_edit.length) {
			$ps_select.appendTo($repo_hidden);
		}

		if ($ps_select.length) {
			$ps_select.on('change', 'select', function(e) {
				var $sel;
				$px_new.appendTo($repo_hidden);
				if ($(this).val()) {
					$px_roles_chk.prop('checked', false);
					$sel = $px_new.filter('[data-persona=' + $(this).val() + ']');
					$sel.insertAfter($ps_select);
					$px_roles_chk.filter('[id=' + idRoleCheckbox + $sel.attr('data-role-id') + ']').prop('checked', true);
				}
			});
		}

		$px_new.appendTo($repo_hidden);

		$ps_edit.removeClass('hidden');
		$ps_select.removeClass('hidden');
		$px_new.removeClass('hidden');

		$ps_select.find('select').change();

		// Click NEW CATEGORY button
		// ********************

		$btn_new_catg.on('click', function (e) {
			var $parent_row = $(this).closest('.row');
			var $new_block = $parent_row.prev(idCatgBlock).clone(false);
			$new_block.find('.select2').remove();
			$new_block.find(idDeleteCatg).removeAttr('disabled');
			$new_block.insertBefore($parent_row).find('.use-select2').select2();
		});

		// Click DELETE CATEGORY button
		// ********************

		$(idEventCriteriaBlock).on('click', idDeleteCatg, function (e) {
			$(this).closest(idCatgBlock).remove();
		});


		$checkbox_employer.on('change' , function(e) {
			var $ins_inputs = $('.js-host-insurance');
			if ($(this).prop('checked')) {
				$ins_inputs.removeClass('hidden');
				return;
			}

			$ins_inputs.addClass('hidden');
		});

		$checkbox_employer.change();

		$form_create_user.on('submit', function (e) {
			if (!$(this).valid()) {
				e.preventDefault();
				return;
			}
		});

	}); // end document.ready


})(jQuery);