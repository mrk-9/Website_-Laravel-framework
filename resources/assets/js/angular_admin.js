(function($, undefined) {
	var app = angular.module('app', ['smart-table', 'ngResource'])
		.config(function($interpolateProvider) {
			$interpolateProvider.startSymbol('[[').endSymbol(']]');
		})
		.config(['$httpProvider', function($httpProvider) {
			// Add X-Requested-With header
			$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
		}]);

	app.run(function($templateCache) {
		$templateCache.put('template/smart-table/pagination.html',
			'<nav ng-if="pages.length >= 2">' +
			'<ul class="pagination">' +
			'<li ng-class="{hidden: 3 >= currentPage}"><a data-ng-click="selectPage(1)" >1</a></li>' +
			'<li class="disabled" ng-class="{hidden: 4 >= currentPage}"><a>...</a></li>' +
			'<li ng-repeat="page in pages" ng-class="{active: page==currentPage}">' +
			'<a data-ng-click="selectPage(page)">{{ page }}</a>' +
			'</li>' +
			'<li class="disabled" ng-class="{hidden: numPages - 3 <= currentPage}"><a>...</a></li>' +
			'<li ng-class="{hidden: numPages - 3 < currentPage}"><a data-ng-click="selectPage(numPages)">[[ numPages ]]</a></li>' +
			'</ul>' +
			'</nav>');
	});

	app.controller('AdminCtrl', function($scope, $resource, $controller) {
		$scope.url = '/admin';

		BaseController.call($scope, $resource);
	});

	app.controller('BuyerCtrl', function($scope, $resource, $controller) {
		$scope.url = '/buyer';

		BaseController.call($scope, $resource);
	});

	app.controller('UserCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/user';

		if ($($element).attr('data-buyer-id')) {
			$scope.url += '?search%5Bbuyer_id%5D=' + $($element).attr('data-buyer-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('BookingCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/booking';

		if ($($element).attr('data-user-id')) {
			$scope.url += '?search%5Bbooking.user_id%5D=' + $($element).attr('data-user-id');
		} else if ($($element).attr('data-ad-placement-id')) {
			$scope.url += '?search%5Bbooking.ad_placement_id%5D=' + $($element).attr('data-ad-placement-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('AuctionCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/auction';

		if ($($element).attr('data-user-id')) {
			$scope.url += '?search%5Bauction.user_id%5D=' + $($element).attr('data-user-id');
		} else if ($($element).attr('data-ad-placement-id')) {
			$scope.url += '?search%5Bauction.ad_placement_id%5D=' + $($element).attr('data-ad-placement-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('OfferCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/offer';

		if ($($element).attr('data-user-id')) {
			$scope.url += '?search%5Boffer.user_id%5D=' + $($element).attr('data-user-id');
		} else if ($($element).attr('data-ad-placement-id')) {
			$scope.url += '?search%5Boffer.ad_placement_id%5D=' + $($element).attr('data-ad-placement-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('AdPlacementCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/emplacement';

		if ($($element).attr('data-media-id')) {
			$scope.url += '?search%5Bad_placement.media_id%5D=' + $($element).attr('data-media-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('AdPlacementWinnerCtrl', function($scope, $resource, $controller) {
		$scope.url = '/ad-placement-earned';

		BaseController.call($scope, $resource);
	});

	app.controller('AcquisitionCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/acquisition';

		if ($($element).attr('data-user-id')) {
			$scope.url += '?search%5Buser_id%5D=' + $($element).attr('data-user-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('SelectionCtrl', function($scope, $element, $resource, $controller) {
		$scope.url = '/selection';

		if ($($element).attr('data-user-id')) {
			$scope.url += '?search%5Buser_id%5D=' + $($element).attr('data-user-id');
		}

		BaseController.call($scope, $resource);
	});

	app.controller('SupportCtrl', function($scope, $resource, $controller) {
		$scope.url = '/support';

		BaseController.call($scope, $resource);
	});

	app.controller('CategoryCtrl', function($scope, $resource, $controller) {
		$scope.url = '/category';

		BaseController.call($scope, $resource);
	});

	app.controller('TargetCtrl', function($scope, $resource, $controller) {
		$scope.url = '/target';

		BaseController.call($scope, $resource);
	});

	app.controller('ThemeCtrl', function($scope, $resource, $controller) {
		$scope.url = '/theme';

		BaseController.call($scope, $resource);
	});

	app.controller('BroadcastingAreaCtrl', function($scope, $resource, $controller) {
		$scope.url = '/broadcasting-area';

		BaseController.call($scope, $resource);
	});

	app.controller('MediaCtrl', function($scope, $resource, $controller) {
		$scope.url = '/media';

		BaseController.call($scope, $resource);
	});

	app.controller('TemplateCtrl', function($scope, $resource, $controller) {
		$scope.url = '/template';

		BaseController.call($scope, $resource);
	});

	app.controller('TechnicalSupportCtrl', function($scope, $resource, $controller) {
		$scope.url = '/technical-support';

		BaseController.call($scope, $resource);
	});

	app.controller('FormatCtrl', function($scope, $resource, $controller) {
		$scope.url = '/format';

		BaseController.call($scope, $resource);
	});

	app.controller('AdNetworkCtrl', function($scope, $resource, $controller) {
		$scope.url = '/ad-network';

		BaseController.call($scope, $resource);
	});

	app.controller('AdNetworkUserCtrl', function($scope, $resource, $controller, $element) {
		$scope.url = '/ad-network-user';

		if ($($element).attr('data-ad-network-id')) {
			$scope.url += '?search%5Bad_network_id%5D=' + $($element).attr('data-ad-network-id');
		}

		BaseController.call($scope, $resource);
	});
})(jQuery);
