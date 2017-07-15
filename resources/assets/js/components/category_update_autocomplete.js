mediaresa.category_update_autocomplete = {
	getData: function(query) {
		var request = $.ajax({
			url: "/api/category" + query,
			datatype: 'json',
			delay: 250,
			cache: true,
		});

		return request;
	},
	init: function(element) {
		element.select2().empty();
		var support_id = element.attr('data-support-id');
		var category_id = element.attr('data-category-id');
		var query = (typeof support_id !== 'undefined') ? "?support_id=" + support_id : "";

		this.getData(query).done(function(request) {
			var categories = $.map(request.categories, function(category) {
				category.text = category.name;

				return category;
			});

			if (categories.length === 0) {
				element.parent().hide();
				element.prop('disabled', true);
			} else {
				// clear existing options and update them with new options
				element.select2().empty();
				element.select2({data: categories}).val(category_id).trigger('change');

				element.prop('disabled', false);
				element.parent().show();
			}
		});
	}
};
