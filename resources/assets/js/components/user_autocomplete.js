mediaresa.user_autocomplete = {
	options: {
		ajax: {
			url: "/api/user",
			datatype: 'json',
			delay: 250,
			data: function(params) {
				return {
					name: params.term,
					buyer_id: $('#UpdateBuyerUser').attr('data-buyer-id')
				};
			},
			processResults: function(data, page) {
				return {
					results: data.users,
				};
			},
			cache: true,
		},
		minimumInputLength: 3,
		templateResult: function(data) {
			if (data.loading) return data.text;
			var markup = data.name + ' ' + data.family_name + ' - ' + data.email;
			return markup;
		},
		templateSelection: function(data) {
			if (data.name) return data.name + ' ' + data.family_name + ' - ' + data.email;
			else return data.text;
		},
	},
	init: function(element) {
		element.select2(this.options);
	},
};
