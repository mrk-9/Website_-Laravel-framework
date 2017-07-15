mediaresa.format_update_autocomplete = {
	getData: function(query) {
		var request = $.ajax({
			url: "/api/format" + query,
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element, media_id) {
		element.select2().empty();
		var format_id = element.attr('data-format-id');
		var query = "?media_id=" + media_id;

		this.getData(query).done(function(request) {
			var formats = $.map(request.formats, function(format) {
				format.text = format.name;

				return format;
			});

			if (formats.length === 0) {
				element.parent().hide();
				element.prop('disabled', true);
			} else {
				// clear existing options and update them with new options
				element.select2().empty();
				element.select2({data: formats}).val(format_id).trigger('change');

				element.prop('disabled', false);
				element.parent().show();
			}
		});
	}
};
