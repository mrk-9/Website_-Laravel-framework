angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalStep9Ctrl', ['$scope', '$mediaresaApi', 'multiStepFormInstance',
        function ($scope, $mediaresaApi, multiStepFormInstance) {
            $scope.buy = function () {
                $scope.order.errors = {};
                return $scope.buyAction('transfer', multiStepFormInstance);
            };
        }]);