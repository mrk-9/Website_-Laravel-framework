mediaresa.target_update_autocomplete = {
	getData: function() {
		var request = $.ajax({
			url: "/api/target",
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	getDataDefaultTargets: function(media_id) {
		var request = $.ajax({
			url: "/api/target?media_id=" + media_id,
			datatype: 'json',
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		var media_id = element.attr('data-media-id');

		this.getData().done(function(request) {
			var targets = $.map(request.targets, function(target) {
				target.text = target.name;

				return target;
			});

			element.select2({data: targets, multiple: true});
		});

		if (typeof media_id !== 'undefined' && media_id !== '') {
			this.getDataDefaultTargets(media_id).done(function(request) {
				var default_targets = request.targets;
				element.select2().val(default_targets).trigger('change');
			});
		}
	}
};

