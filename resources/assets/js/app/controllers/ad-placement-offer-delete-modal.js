angular.module('mediaresa-app')
    .controller('AdPlacementOfferDeleteModalCtrl', ['$scope', '$window', '$mediaresaApi', '$uibModalInstance', 'adPlacementId',
        function ($scope, $window, $mediaresaApi, $uibModalInstance, adPlacementId) {

            $scope.delete = function () {
                return $mediaresaApi.deleteAdPlacementOffer(adPlacementId).then(function () {
                    $uibModalInstance.close();
                });
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };

        }]);