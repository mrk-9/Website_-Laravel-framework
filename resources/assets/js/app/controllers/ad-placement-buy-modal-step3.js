angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalStep3Ctrl', ['$scope', '$mediaresaApi',
        function ($scope, $mediaresaApi) {
            $mediaresaApi.getTechnicalSupports().then(function(response) {
                $scope.technicalSupports = response.data;
            });
        }]);