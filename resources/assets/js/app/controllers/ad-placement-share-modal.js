angular.module('mediaresa-app')
    .controller('AdPlacementShareModalCtrl', ['$scope', '$timeout', '$mediaresaApi', '$uibModalInstance', 'adPlacementId',
        function ($scope, $timeout, $mediaresaApi, $uibModalInstance, adPlacementId) {

            $mediaresaApi.getAdPlacement(adPlacementId).then(function (response) {
                $scope.adPlacement = response.data;
            });

            $scope.send = function () {
                return $mediaresaApi.shareAdPlacement(adPlacementId, $scope.contact).then(function () {
                    $scope.msgSend = true;
                    $timeout(function() {
                        $uibModalInstance.close();
                    }, 2000);
                });
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };

        }]);