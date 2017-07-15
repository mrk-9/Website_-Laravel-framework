mediaresa.ad_network_user_autocomplete = {
	options: {
		ajax: {
			url: "/api/ad-network-user",
			datatype: 'json',
			delay: 250,
			data: function(params) {
				return {
					name: params.term,
					ad_network: $('#UpdateAdNetworkUser').attr('data-ad-network-id'),
				};
			},
			processResults: function(data, page) {
				return {
					results: data.ad_network_users,
				};
			},
			cache: true,
		},
		minimumInputLength: 0,
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
