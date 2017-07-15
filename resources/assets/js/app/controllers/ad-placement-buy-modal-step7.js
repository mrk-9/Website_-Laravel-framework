angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalStep7Ctrl', ['$scope',
        function ($scope) {
            $scope.$watch('order.price', function() {
                $scope.order.depositPrice = Math.round($scope.order.price * ($scope.adPlacement.deposit_percent / 100) * 100) / 100;
                if ($scope.order.technicalSupport === undefined) {
                    $scope.order.total = $scope.order.depositPrice;
                } else {
                    $scope.order.total = $scope.order.depositPrice + $scope.order.technicalSupport.price;
                }
                $scope.order.rate = Math.round((($scope.adPlacement.vat_rate / 100) * $scope.order.total) * 100) / 100;
                $scope.order.totalWithRate = $scope.order.total + $scope.order.rate;
                $scope.order.adNetworkPrice = $scope.order.price - $scope.order.depositPrice;
            });
        }]);