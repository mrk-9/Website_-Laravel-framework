mediaresa.media_autocomplete = {
	options: {
		ajax: {
			url: "/api/media",
			datatype: 'json',
			delay: 250,
			data: function(params) {
				return {
					name: params.term
				};
			},
			processResults: function(data, page) {
				return {
					results: data.medias,
				};
			},
			cache: true,
		},
		minimumInputLength: 1,
		templateResult: function(data) {
			if (data.loading) return data.text;
			var markup = data.name;
			return markup;
		},
		templateSelection: function(data) {
			if (data.name) return data.name;
			else return data.text;
		},
	},
	init: function(element) {
		element.select2(this.options);
	},
};
