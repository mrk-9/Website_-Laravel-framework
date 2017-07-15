angular.module('mediaresa-app')
    .controller('ContactModalCtrl', ['$scope', '$timeout', '$mediaresaApi', '$uibModalInstance',
        function ($scope, $timeout, $mediaresaApi, $uibModalInstance) {

            $scope.conctact = {};

            $scope.send = function () {
                return $mediaresaApi.sendContact($scope.contact).then(function (response) {
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