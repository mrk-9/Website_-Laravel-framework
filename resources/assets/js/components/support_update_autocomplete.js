mediaresa.support_update_autocomplete = {
	getData: function() {
		var request = $.ajax({
			url: "/api/support",
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		var _this = this;
		var support_id= element.attr('data-support-id');

		this.getData().done(function(data) {
			var supports = $.map(data.supports, function(support) {
				support.text = support.name;

				return support;
			});

			element.select2({
				data: supports,
			}).val(support_id).trigger('change');

			_this.initDataSupportId(element);
			_this.updateDataSupportId(element);
		});
	},
	initDataSupportId: function(element) {
		$('select[data-support-id]').attr('data-support-id', $(element).val());
		mediaresa.category_update_autocomplete.init($('select.autocomplete-category-update'));
		mediaresa.theme_update_autocomplete.init($('select.autocomplete-theme-update'));
	},
	updateDataSupportId: function(element) {
		$(element).on('change', function() {
			$('select[data-support-id]').attr('data-support-id', $(element).val());
			mediaresa.category_update_autocomplete.init($('select.autocomplete-category-update'));
			mediaresa.theme_update_autocomplete.init($('select.autocomplete-theme-update'));
		});
	}
};
