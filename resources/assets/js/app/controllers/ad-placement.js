angular.module('mediaresa-app')
    .controller('AdPlacementCtrl', ['$scope', '$uibModal',
        function ($scope, $uibModal) {

            $scope.buy = function (id, type){
                $uibModal.open({
                    animation: true,
                    templateUrl: '/partials/modal/ad-placement-buy.html',
                    controller: 'AdPlacementBuyModalCtrl',
                    size: 'lg',
                    backdrop: 'static',
                    resolve: {
                        adPlacementId: function () {
                            return id;
                        },
                        buyType: function () {
                            return type;
                        }
                    }
                });
            };

            $scope.share = function (id){
                $uibModal.open({
                    animation: true,
                    templateUrl: '/partials/modal/ad-placement-share.html',
                    controller: 'AdPlacementShareModalCtrl',
                    size: 'lg',
                    resolve: {
                        adPlacementId: function () {
                            return id;
                        }
                    }
                });
            };

            $scope.deleteOffer = function (id){
                $uibModal.open({
                    animation: true,
                    templateUrl: '/partials/modal/ad-placement-offer-delete.html',
                    controller: 'AdPlacementOfferDeleteModalCtrl',
                    resolve: {
                        adPlacementId: function () {
                            return id;
                        }
                    }
                });
            };

        }]);