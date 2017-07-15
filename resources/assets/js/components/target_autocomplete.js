mediaresa.target_autocomplete = {
	getData: function() {
		var request = $.ajax({
			url: "/api/target",
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		this.getData().done(function(request) {
			var targets = $.map(request.targets, function(target) {
				target.text = target.name;

				return target;
			});

			element.select2({data: targets, multiple: true});
		});
	}
};

