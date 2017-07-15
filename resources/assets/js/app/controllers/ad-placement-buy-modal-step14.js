angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalStep14Ctrl', ['$scope', '$mediaresaApi', 'multiStepFormInstance',
        function ($scope, $mediaresaApi, multiStepFormInstance) {
            $mediaresaApi.getAdPlacement($scope.adPlacement.id).then(function (response) {
                $scope.adPlacement = response.data;
                $scope.currentPrice = $scope.adPlacement.price;
            });

            $scope.changePrice = function() {
                $scope.order = {};
                $scope.order.buyType = 'auction';
                $scope.order.errors = {};
                $scope.order.price = $scope.adPlacement.user_min_price;
                multiStepFormInstance.cleanHistory();
                multiStepFormInstance.setActiveIndex(1);
            }
        }]);