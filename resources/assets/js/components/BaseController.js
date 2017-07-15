var BaseController = function($resource) {
	var _this = this;

	this.go = function(url) {
		window.location.href = url;
	};

	this.displayPanel = false;
	this.loading = false;

	this.errorCallback = function(data) {
		if (data.status == 401) {
			window.alert('Votre session a expirée.\n Vous allez être redirigé vers la page de connexion.');
			location.href = path + '/login';

			return false;
		}
		else {
			window.alert('Erreur ' + data.status);
		}
	};

	this.displayInPanel = function(object, event) {
		_this.current = angular.copy(object);
		_this.displayPanel = true;

		if (undefined !== event) {
			$(event.currentTarget).parents('tbody').find('tr').removeClass('active');
			$(event.currentTarget).addClass('active');
		}
	};

	this.refresh = function(tableState) {
		_this.loading = true;
		_this.tableState = tableState ? tableState : _this.tableState;

		var page = Math.floor(_this.tableState.pagination.start / _this.tableState.pagination.number) + 1;
		var sort = _this.tableState.sort;
		var per_page = _this.tableState.pagination.number;

		var params = {
			page : page,
			sort: sort,
			per_page : per_page,
		};

		if (_this.tableState.search && _this.tableState.search.predicateObject) {
			var predicates = _this.tableState.search.predicateObject;

			for (var key in predicates) {
				if (predicates.hasOwnProperty(key)) {
					params['search[' + key.replace(/__/g, '.') + ']'] = predicates[key];
				}
			}
		}

		var api = $resource(_this.url);

		api.get(params, function(data) {
			_this.list = data.data;
			_this.tableState.pagination.numberOfPages = data.last_page;
			_this.tableState.pagination.start = data.from;
			_this.tableState.pagination.totalItemCount = data.total;
			_this.displayInPanel(_this.list[0]);
			_this.loading = false;
		}, _this.errorCallback);
	};

	this.openModal = function(selector, object) {
		$(selector).modal('show');

		_this.current = angular.copy(object);

		$('form.ajax', $(selector)).each(function() {
			var action = decodeURI($(this).attr('action'));

			$(this).formajax('setUrl', action.formatUrl(object));
		});
	};
};
