mediaresa.theme_update_autocomplete = {
	getData: function(query) {
		var request = $.ajax({
			url: "/api/theme" + query,
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		var support_id = element.attr('data-support-id');
		var theme_id = element.attr('data-theme-id');
		var query = (typeof support_id !== 'undefined') ? "?support_id=" + support_id : "";

		this.getData(query).done(function(request) {
			var themes = $.map(request.themes, function(theme) {
				theme.text = theme.name;

				return theme;
			});

			if (themes.length === 0) {
				element.parent().hide();
				element.prop('disabled', true);
			} else {
				// clear existing options and update them with new options
				element.select2().empty();
				element.select2({data: themes}).val(theme_id).trigger('change');

				// show element
				element.prop('disabled', false);
				element.parent().show();
			}
		});
	}
};
