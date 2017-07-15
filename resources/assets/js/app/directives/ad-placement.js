angular.module('mediaresa-app').directive('adPlacement', function () {
    return {
        restrict: 'E',
        scope: {
            adPlacement: '=ngModel',
            price: '=?price'
        },
        templateUrl: '/partials/directive/ad-placement.html'
    };
});

