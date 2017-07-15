String.prototype.formatUrl = function(tokens) {
	var str = this;
	var formatted = str.replace(/\{([A-Za-z0-9.]+)\}/g, function(match, idx) {
		var value = idx.split('.').reduce(function(acc, cur) {
			if (acc === false) return acc;
			else if (typeof acc === 'object' && acc.hasOwnProperty(cur)) return acc[cur];
			else return false;
		}, tokens);

		return (value === false) ? match : value;
	});
	return formatted;
};

$(function() {

	/**
	* Detect hash code inside URL and open modal
	* We avoid to match facebook hash : '#_=_'
	*/
	if ($(location)[0].hash && $(location)[0].hash !== '#_=_' && $($(location)[0].hash + '.modal')) {
		$($(location)[0].hash + '.modal').modal('show');
	}

	// Set X-CSRF-TOKEN header for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	// Fix for Select2 dropdowns in BS3 modals
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};

	// reset forms
	$('.modal').on('hidden.bs.modal', function() {
		if ($('form', this).length) {
			$('form', this)[0].reset();
		}

		$('.form-group').removeClass('has-error');
		$('.alert.alert-danger').addClass('hidden');
	});

	/**
	 * Initialize datetimepickers
	 */
	$([
		'#CreateAdPlacementStartingAt',
		'#CreateAdPlacementEndingAt',
		'#CreateAdPlacementTechnicalDeadline',
		'#UpdateAdPlacementStartingAt',
		'#UpdateAdPlacementEndingAt',
		'#UpdateAdPlacementTechnicalDeadline',
		'#CreateAdPlacementLockingUp',
		'#UpdateAdPlacementLockingUp'
	]).datetimepicker({
		format: 'd/m/Y H:i',
		lang: 'fr',
	});

	/**
	 * Initialize datetimepickers without H:i
	 */
	$([
		'#CreateAdPlacementBroadcastingDate',
		'#UpdateAdPlacementBroadcastingDate'
	]).datetimepicker({
		format: 'd/m/Y',
		lang: 'fr',
		timepicker: false,
	});

	/**
	 * Initialize autocompletes
	 */
	mediaresa.support_autocomplete.init($('select.autocomplete-support'));
	mediaresa.user_autocomplete.init($('select.autocomplete-user'));
	mediaresa.media_autocomplete.init($('select.autocomplete-media'));
	mediaresa.ad_network_autocomplete.init($('select.autocomplete-ad-network'));
	mediaresa.broadcasting_area_autocomplete.init($('select.autocomplete-broadcasting-area'));
	mediaresa.target_autocomplete.init($('select.autocomplete-target'));
	mediaresa.format_autocomplete.init($('select.autocomplete-format'));
	mediaresa.ad_network_user_autocomplete.init($('select.autocomplete-ad-network-user'));

	$('form.ajax').formajax({
		done : function(data, textStatus, response) {
			if (data.redirect && data.redirect.length !== 0) {
				window.location.href = data.redirect;
			}
			else if (data.reload && data.reload == 1) {
				window.location.reload();
			}
			else if (data.modal && data.modal.length > 0) {
				$('.modal.in').modal('hide');

				setTimeout(function() {
					$(data.modal).modal('show');
				}, 1000);
			}
			else if ($(this.form).data('formajax-success')) {
				var success = $(this.form).data('formajax-success');

				if (/redirect\(.*\)/.test(success)) {
					var url = decodeURI(success.match(/redirect\((.*)\)/)[1]).formatUrl(data);

					window.location.href = url;
				}
				else if (/reload/.test(success)) {
					window.location.reload();
				}
				else if (/modal/.test(success)) {
					$('.modal.in').modal('hide');
				}
			}

			var table = $(this.form).data('referent-table');

			if (table && table.length && $('table[data-st-table=' + table + ']').length) {
				angular.element($('table[data-st-table=' + table + ']'))
					.scope()
					.refresh();

				setTimeout(function() {
					$('.modal.in').modal('hide');
				}, 1000);
			}
		},
		http: {
			_422: function(response) {
				var errors = response.responseJSON;
				var html = this.settings.formatErrors.call(this, errors);

				$(this.settings.error, this.form).append(html).removeClass('hidden');
			},
			_403 : function(response) {
				var forbiddens = response.responseJSON;
				var html = this.settings.formatErrors.call(this, forbiddens);

				$(this.settings.error, this.form).append(html).removeClass('hidden');
			},
			_404 : function(response) {
				var errors = response.responseJSON;
				var html = this.settings.formatErrors.call(this, errors);

				$(this.settings.error, this.form).append(html).removeClass('hidden');
			},
		},
		getFormData : function(form) {
			var data = form.serializeArray();

			$('.summernote', $(form)).each(function() {
				data.push({
					name: $(this).data('name'),
					value: $(this).code(),
				});
			});

			return data;
		},
		error : '.alert.alert-danger',
	});

	/**
	 * Show / hide activity field or customer field
	 * Modal : UpdateBuyerModal
	 */
	$('#UpdateBuyerModal').on('shown.bs.modal change', function() {
		mediaresa.user_autocomplete.init($('select.autocomplete-user'));

		var buyer_type = $('#UpdateBuyerType', $(this)).val();
		var customer_field = $('#UpdateBuyerCustomer', $(this)).parent();
		var activity_field = $('#UpdateBuyerActivity', $(this)).parent();

		if (buyer_type === 'advertiser') {
			activity_field.show();
			customer_field.hide();
		} else {
			activity_field.hide();
			customer_field.show();
		}
	});

	/**
	 * Initialize fields by hiding or disabling them.
	 * Modal CreateAdPlacementModal.
	 */
	$('#CreateAdPlacementModal').on('show.bs.modal', function() {
		mediaresa.format_create_autocomplete.init($('select.autocomplete-format-create'));

		var edition = $('#CreateAdPlacementEdition', $(this))
		var ad_placement_type = $('#AdPlacementTypeList', $(this));
		var minimum_price = $('#CreateAdPlacementMinimumPrice', $(this));

		edition.prop('disabled', true);
		edition.parent().hide();

		listenHideOrShowMinimumPrice(minimum_price, ad_placement_type.val())
	});

	/**
	 * Enable or disable (and show / hide) edition field.
	 * Modal CreateAdPlacementModal.
	 */
	$('#CreateAdPlacementMedia', '#CreateAdPlacementModal').on('change', function() {
		var edition = $('#CreateAdPlacementEdition', '#CreateAdPlacementModal');
		var support_name = getSelect2OptionText($(this), 'support.name').toLowerCase();
		var support_id = getSelect2OptionText($(this), 'support.id');
		var format = $('#CreateAdPlacementFormat', '#CreateAdPlacementModal');

		listenHideOrShowEdition(edition, support_name);
		attachDataSupportId(format, support_id);
		mediaresa.format_create_autocomplete.init($('select.autocomplete-format-create'));
	});

	/**
	 * Enable or disable (and show / hide) minimum_price field.
	 * Modal CreateAdPlacementModal.
	 */
	$('#AdPlacementTypeList', '#CreateAdPlacementModal').on('change', function() {
		var minimum_price = $('#CreateAdPlacementMinimumPrice', '#CreateAdPlacementModal');

		listenHideOrShowMinimumPrice(minimum_price, $(this).val())
	});

	/**
	 * Enable or disable (and show / hide) minimum_price field.
	 * AdNetwork / Media / ad_placement / create
	 */
	$('#AdPlacementTypeList', '#CreateAdPlacementForm').on('change', function() {
		var form = $('#CreateAdPlacementForm');
		var ad_placement_type = $('#AdPlacementTypeList', form);
		var minimum_price = $('#CreateAdPlacementMinimumPrice', form);

		listenHideOrShowMinimumPrice(minimum_price, ad_placement_type.val())
	});

	/**
	 * Shown event is necessary to initialize with data binding.
	 * Modal UpdateAdPlacementModal.
	 */
	$('#UpdateAdPlacementModal').on('shown.bs.modal', function() {
		// event on change for UpdateAdPlacementMedia will be triggered by media_update_autocomplete
		// during initialization.
		mediaresa.media_update_autocomplete.init($('select.autocomplete-media-update'));
		hideOrShowAcquisitionsPresence($(this));
	});

	$('#UpdateAdPlacementTypeList', '#UpdateAdPlacementModal').on('change', function() {
		var minimum_price = $('#UpdateAdPlacementMinimumPrice', '#UpdateAdPlacementModal');
		var acquisitions_length = $('#UpdateAdPlacementModal').attr('data-acquisitions-length');

		if (typeof acquisitions_length !== 'undefined') {
			listenHideOrShowMinimumPrice(minimum_price, $(this).val());
		}
	});

	/**
	 * Enable or disable (and show / hide) edition field.
	 * Modal CreateAdPlacementModal.
	 */
	$('#UpdateAdPlacementMedia', '#UpdateAdPlacementModal').on('change', function() {
		mediaresa.format_update_autocomplete.init($('select.autocomplete-format-update'), $(this).val());

		var edition = $('#UpdateAdPlacementEdition', '#UpdateAdPlacementModal');
		var minimum_price = $('#UpdateAdPlacementMinimumPrice', '#UpdateAdPlacementModal');
		var support_name = getSelect2OptionText($(this), 'support.name').toLowerCase();
		var ad_placement_type = $('#UpdateAdPlacementTypeList', '#UpdateAdPlacementModal');
		var acquisitions_length = $('#UpdateAdPlacementModal').attr('data-acquisitions-length');

		listenHideOrShowEdition(edition, support_name);

		if (typeof acquisitions_length !== 'undefined') {
			listenHideOrShowMinimumPrice(minimum_price, ad_placement_type.val());
		}
	});

	/**
	 * Reload select2
	 * Modal UpdateAdPlacementModal.
	 */
	$('#UpdateSupportTypeModal').on('show.bs.modal', function() {
		// reload select2 to get angular JS data binding.
		mediaresa.category_autocomplete.init($('select.autocomplete-category'));
		mediaresa.support_autocomplete.init($('select.autocomplete-support'));
	});

	/**
	 * Reload select2
	 * Modal UpdateMediaModal.
	 */
	$('#UpdateMediaModal').on('shown.bs.modal', function() {
		// reload select2 to get angular JS data binding.
		mediaresa.support_update_autocomplete.init($('select.autocomplete-support-update', $(this)));
		mediaresa.ad_network_update_autocomplete.init($('select.autocomplete-ad-network-update', $(this)));
		mediaresa.broadcasting_area_update_autocomplete.init($('select.autocomplete-broadcasting-area-update', $(this)));
		mediaresa.target_update_autocomplete.init($('select.autocomplete-target-update', $(this)));
	});

	/**
	 * Initialize select2 in UpdateMediaForm (ad network)
	 */
	mediaresa.support_update_autocomplete.init($('select.autocomplete-support-update'), $('#UpdateMediaForm'));
	mediaresa.ad_network_update_autocomplete.init($('select.autocomplete-ad-network-update', $('#UpdateMediaForm')));
	mediaresa.broadcasting_area_update_autocomplete.init($('select.autocomplete-broadcasting-area-update', $('#UpdateMediaForm')));
	mediaresa.target_update_autocomplete.init($('select.autocomplete-target-update', $('#UpdateMediaForm')));

	/**
	 * Initialize select2 in CreateAdPlacementForm (ad network)
	 */
	if ($('#CreateAdPlacementForm') && $('#CreateAdPlacementForm').length > 0) {
		mediaresa.format_create_autocomplete.init($('select.autocomplete-format-create', $('#CreateAdPlacementForm')));
		var edition = $('#CreateAdPlacementEdition', $('#CreateAdPlacementForm'));
		var ad_placement_type = $('#AdPlacementTypeList', $('#CreateAdPlacementForm'));
		var minimum_price = $('#CreateAdPlacementMinimumPrice', $('#CreateAdPlacementForm'));
		var support_name = edition.attr('data-support-name');

		if (support_name && support_name.length > 0) {
			listenHideOrShowEdition(edition, edition.attr('data-support-name'));
		}

		listenHideOrShowMinimumPrice(minimum_price, ad_placement_type.val());
	}

	/**
	 * Initialize fields and retreive remote data
	 * Form CreateAdPlacementForm (ad network).
	 */
	$('#CreateAdPlacementMedia', '#CreateAdPlacementForm').on('change', function() {
		var edition = $('#CreateAdPlacementEdition', '#CreateAdPlacementForm');
		var support_name = getSelect2OptionText($(this), 'support.name').toLowerCase();
		var support_id = getSelect2OptionText($(this), 'support.id');
		var format = $('#CreateAdPlacementFormat', '#CreateAdPlacementForm');

		listenHideOrShowEdition(edition, support_name);
		attachDataSupportId(format, support_id);
		mediaresa.format_create_autocomplete.init($('select.autocomplete-format-create', $('#CreateAdPlacementForm')));
	});

	/**
	 * Reload select2
	 * Modal UpdateAdNetworkModal.
	 */
	$('#UpdateAdNetworkModal').on('shown.bs.modal', function() {
		// reload select2 to get angular JS data binding.
		mediaresa.ad_network_user_autocomplete.init($('select.autocomplete-ad-network-user'));
	});

	$('#EndOfCreatingMediaModal').on('hidden.bs.modal', function () {
		window.location.hash = '#';
		window.location.reload();
	});

	function listenHideOrShowMinimumPrice(minimum_price, ad_placement_type) {
		if (ad_placement_type === 'booking' || ad_placement_type === 'auction') {
			minimum_price.prop('disabled', true);
			minimum_price.parent().hide();
		} else {
			minimum_price.prop('disabled', false);
			minimum_price.parent().show();
		}
	}

	function listenHideOrShowEdition(edition, support_name) {
		if (support_name.toLowerCase() === 'presse') {
			edition.prop('disabled', false);
			edition.parent().show();
		} else {
			edition.prop('disabled', true);
			edition.parent().hide();
		}
	}

	/**
	 * Hide or show fields from a given ad placement modals
	 * if acquisition exists.
	 */
	function hideOrShowAcquisitionsPresence(modal) {
		var acquisitions_length = modal.attr('data-acquisitions-length');

		if (acquisitions_length !== 'undefined') {
			var elements = $('.check-acquisitions-presence', modal)

			if (acquisitions_length > 0) {
				elements.prop('disabled', true);
				elements.parent().hide();
			} else {
				elements.prop('disabled', false);
				elements.parent().show();
			}
		}
	}

	function getSelect2OptionText(select, relationships) {
		var relationships_keys = splitRelationShips(relationships);
		var current = select.select2('data')[0];

		// iterate on relationships to get the value
		relationships_keys.forEach(function(relation) {
			current = current[relation];
		});

		return current;
	}

	function splitRelationShips(relationships_string) {
		return relationships_string.split('.');
	}

	function attachDataSupportId(element, support_id) {
		element.attr('data-support-id', support_id);
	}

	$('.input-file').each(function() {
		var $input = $(this),
			$label = $input.next('.js-labelFile'),
			labelVal = $label.html();

		$input.on('change', function(element) {
			var fileName = '';
			if (element.target.value) fileName = element.target.value.split('\\').pop();
			fileName ? $label.addClass('has-file').find('.js-fileName').html(fileName) : $label.removeClass('has-file').html(labelVal);
		});
	});

});
