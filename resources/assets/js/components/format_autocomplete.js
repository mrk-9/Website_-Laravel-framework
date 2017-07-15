mediaresa.format_autocomplete = {
	getData: function() {
		var request = $.ajax({
			url: "/api/format",
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		this.getData().done(function(data) {
			var formats = $.map(data.formats, function(format) {
				format.text = format.name;

				return format;
			});

			element.select2({
				data: formats,
			});
		});
	}
};
