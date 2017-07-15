mediaresa.format_create_autocomplete = {
	getData: function(query) {
		var request = $.ajax({
			url: "/api/format" + query,
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		var support_id = element.attr('data-support-id');

		if ((typeof support_id) === 'undefined' || support_id === '') {
			element.parent().hide();
			element.prop('disabled', true);
		} else {
			var query = "?support_id=" + support_id;

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
					element.select2({data: formats});

					element.prop('disabled', false);
					element.parent().show();
				}
			});
		}
	}
};
