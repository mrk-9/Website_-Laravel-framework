mediaresa.broadcasting_area_update_autocomplete = {
	getData: function() {
		var request = $.ajax({
			url: "/api/broadcasting-area",
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		var broadcasting_area_id = element.attr('data-broadcasting-area-id');

		this.getData().done(function(data) {
			var broadcasting_areas = $.map(data.broadcasting_areas, function(broadcasting_area) {
				broadcasting_area.text = broadcasting_area.name;

				return broadcasting_area;
			});

			element.select2({
				data: broadcasting_areas,
			}).val(broadcasting_area_id).trigger('change');
		});
	}
};
