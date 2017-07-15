angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalStep10Ctrl', ['$scope', '$mediaresaApi', 'multiStepFormInstance', 'stripe',
        function ($scope, $mediaresaApi, multiStepFormInstance, stripe) {
            $mediaresaApi.getCreditCard().then(function(response) {
                $scope.creditCard = response.data.card;
                if($scope.creditCard !== null) {
                    $scope.order.stripeSaveCard = true;
                }
            });

            $scope.$watch('newCreditCard', function() {
                var card = [];
                if ($scope.newCreditCard !== undefined) {
                    card = Object.keys($scope.newCreditCard).filter(function(r) {
                        return !($scope.newCreditCard[r] === null || $scope.newCreditCard[r] === undefined || $scope.newCreditCard[r].length === 0);
                    });
                }
                card.length ? $scope.hasNewCard = true : $scope.hasNewCard = false;
            }, true);

            $scope.buy = function() {
                $scope.order.errors = {};
                if($scope.hasNewCard){
                    return stripe.card.createToken($scope.newCreditCard).then(function(response) {
                            $scope.order.stripeToken = response.id;
                            return $scope.buyAction('credit_card', multiStepFormInstance);
                        }, function(response) {
                            $scope.order.errors[response.code] = true;
                        });
                } else {
                    return $scope.buyAction('credit_card', multiStepFormInstance);
                }
            };
        }]);