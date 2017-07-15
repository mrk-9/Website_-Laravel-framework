angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalCtrl', ['$scope', '$window', '$mediaresaApi', '$uibModalInstance', 'adPlacementId', 'buyType',
        function ($scope, $window, $mediaresaApi, $uibModalInstance, adPlacementId, buyType) {

            $scope.initStep = 1;
            if(buyType === 'booking') {
                $scope.initStep = 2;
            }

            $scope.order = {};
            $scope.order.buyType = buyType;
            $scope.order.errors = {};

            $mediaresaApi.getAdPlacement(adPlacementId).then(function (response) {
                $scope.adPlacement = response.data;
                if(buyType === 'booking') {
                    $scope.order.price = $scope.adPlacement.price;
                } else if (buyType === 'offer') {
                    $scope.order.price = $scope.adPlacement.user_max_price;
                } else {
                    $scope.order.price = $scope.adPlacement.user_min_price;
                }

            });

            $scope.steps = [
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step1.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step2.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step3.html',
                    controller: 'AdPlacementBuyModalStep3Ctrl'
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step4.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step5.html',
                    controller: 'AdPlacementBuyModalStep5Ctrl'
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step6.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step7.html',
                    controller: 'AdPlacementBuyModalStep7Ctrl'
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step8.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step9.html',
                    controller: 'AdPlacementBuyModalStep9Ctrl'
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step10.html',
                    controller: 'AdPlacementBuyModalStep10Ctrl'
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step11.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step12.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step13.html',
                },
                {
                    templateUrl: '/partials/modal/ad-placement-buy-step14.html',
                    controller: 'AdPlacementBuyModalStep14Ctrl'
                }
            ];

            $scope.close = function () {
                $window.location.reload();
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };

            $scope.buyAction = function(type, multiStepFormInstance) {
                return $mediaresaApi.buyAdPlacement($scope.adPlacement.id, type, $scope.order).then(function () {
                    multiStepFormInstance.setActiveIndex(11);
                }, function(response) {
                    var error = response.data[Object.keys(response.data)[0]];
                    if(error === "ad_placement_bought") {
                        multiStepFormInstance.setActiveIndex(12);
                    } else if(error === "ad_placement_locked") {
                        multiStepFormInstance.setActiveIndex(13);
                    } else if(error === "price_too_low") {
                        multiStepFormInstance.setActiveIndex(14);
                    } else {
                        $scope.order.errors[error] = true;
                    }
                });
            }

        }]);