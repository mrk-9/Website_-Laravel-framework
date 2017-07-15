mediaresa.media_update_autocomplete = {
	getData: function() {
		var request = $.ajax({
			url: "/api/media",
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		var _this = this;
		var media_id = element.attr('data-media-id');

		this.getData().done(function(data) {
			var medias = $.map(data.medias, function(media) {
				media.text = media.name;

				return media;
			});

			element.select2({
				data: medias,
			}).val(media_id).trigger('change');

			_this.initDataMediaId(element);
		});
	},
	initDataMediaId: function(element) {
		$('select[data-media-id]').attr('data-media-id', $(element).val());
	}
};
