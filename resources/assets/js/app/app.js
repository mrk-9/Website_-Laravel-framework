var app = angular.module('mediaresa-app', [
    'ngMessages',
    'ui.bootstrap',
    'angularPromiseButtons',
    'multiStepForm',
    'angular.filter',
    'angular-stripe'
]);

app.run(['$rootScope', '$uibModal',
    function ($rootScope, $uibModal) {

        $rootScope.app = {};

        $rootScope.contactModal = function (){
            $uibModal.open({
                animation: true,
                templateUrl: '/partials/modal/contact.html',
                controller: 'ContactModalCtrl',
                size: 'lg',
            });
        };

    }
]);


app.config(['$interpolateProvider', '$locationProvider', 'stripeProvider',
    function ($interpolateProvider, $locationProvider, stripeProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');

        stripeProvider.setPublishableKey(stripePublicKey);
        
    }]);


app.filter('mcDbDateFormatter', function () {
    return function (dateSTR) {
        if (dateSTR !== undefined) {
            var o = dateSTR.replace(/-/g, "/"); // Replaces hyphens with slashes
            return Date.parse(o + " -0000"); // No TZ subtraction on this sample
        }
    }
});

app.filter('nl2br', function ($sce) {
    return function (msg, is_xhtml) {
        var is_xhtml = is_xhtml || true;
        var breakTag = (is_xhtml) ? '<br />' : '<br>';
        var msg = (msg + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        return $sce.trustAsHtml(msg);
    }
});

app.filter('isEmptyObject', function () {
    return function (obj) {
        return angular.equals({}, obj);
    };
});