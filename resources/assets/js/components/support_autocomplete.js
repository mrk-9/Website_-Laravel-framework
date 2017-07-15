mediaresa.support_autocomplete = {
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
		var _this = this;

		this.getData().done(function(data) {
			var supports = $.map(data.supports, function(support) {
				support.text = support.name;

				return support;
			});

			element.select2({
				data: supports,
			});

			_this.initDataSupportId(element);
			_this.updateDataSupportId(element);
		});
	},
	initDataSupportId: function(element) {
		$('select[data-support-id]').attr('data-support-id', $(element).val());
		mediaresa.category_autocomplete.init($('select.autocomplete-category'));
		mediaresa.theme_autocomplete.init($('select.autocomplete-theme'));

	},
	updateDataSupportId: function(element) {
		$(element).on('change', function() {
			$('select[data-support-id]').attr('data-support-id', $(element).val());
			mediaresa.category_autocomplete.init($('select.autocomplete-category'));
			mediaresa.theme_autocomplete.init($('select.autocomplete-theme'));
		});
	}
};
