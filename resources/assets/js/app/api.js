angular.module('mediaresa-app')
    .factory('$mediaresaApi', ['$http',
        function ($http) {

            var urlBase = '/api';

            var urlBaseAdPlacements = urlBase + '/ad_placement';
            var urlBaseAdPlacement = function(id) {
                return urlBaseAdPlacements + '/' + id;
            };
            var urlBaseAdPlacementBuy = function(id) {
                return urlBaseAdPlacement(id) + '/buy';
            };
            var urlBaseAdPlacementShare = function(id) {
                return urlBaseAdPlacement(id) + '/share';
            };

            var urlBaseTechnicalSupports = urlBase + '/technical_support';
            var urlBaseTemplates = urlBase + '/template';

            var urlBaseSupports = urlBase + '/support';
            var urlBaseSupport = function(supportId) {
                return urlBaseSupports + '/' + supportId;
            };

            var urlBaseCreditCard = urlBase + '/credit_card';


            var dataFactory = {};

            dataFactory.sendContact = function (contact) {
                return $http.post(urlBase + '/contact', contact);
            };

            dataFactory.getAdPlacement = function (id) {
                return $http.get(urlBaseAdPlacement(id));
            };

            dataFactory.getTechnicalSupports = function () {
                return $http.get(urlBaseTechnicalSupports);
            };

            dataFactory.getTemplates = function () {
                return $http.get(urlBaseTemplates);
            };

            dataFactory.getSupportCategories = function (supportId) {
                return $http.get(urlBaseSupport(supportId) + '/category');
            };

            dataFactory.getSupportFormats = function (supportId) {
                return $http.get(urlBaseSupport(supportId) + '/format');
            };

            dataFactory.getSupportThemes = function (supportId) {
                return $http.get(urlBaseSupport(supportId) + '/theme');
            };

            dataFactory.getCreditCard = function () {
                return $http.get(urlBaseCreditCard);
            };

            dataFactory.buyAdPlacement = function (adPlacementId, paymentType, order) {
                order.paymentType = paymentType;
                return $http.post(urlBaseAdPlacementBuy(adPlacementId), order);
            };

            dataFactory.deleteAdPlacementOffer = function (adPlacementId) {
                return $http.delete(urlBaseAdPlacementBuy(adPlacementId));
            };

            dataFactory.shareAdPlacement = function (adPlacementId, contact) {
                return $http.post(urlBaseAdPlacementShare(adPlacementId), contact);
            };

            return dataFactory;
        }]);