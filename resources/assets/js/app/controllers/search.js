angular.module('mediaresa-app')
    .controller('SearchCtrl', ['$scope', '$mediaresaApi', '$window',
        function ($scope, $mediaresaApi, $window) {

            function getUrlVars() {
                var vars = {};
                var parts = $window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars;
            }

            $scope.search = {};
            $scope.search.support = getUrlVars()['support'] === undefined ? 'all' : getUrlVars()['support'];
            $scope.search.category = getUrlVars()['category'] === undefined ? 'all' : getUrlVars()['category'];
            $scope.search.theme = getUrlVars()['theme'] === undefined ? 'all' : getUrlVars()['theme'];
            $scope.search.format = getUrlVars()['format'] === undefined ? 'all' : getUrlVars()['format'];

            $scope.updateSupport = function(init) {
                if(!init) {
                    $scope.search.category = 'all';
                    $scope.search.theme = 'all';
                    $scope.search.format = 'all';
                }

                $scope.categories = [];
                $scope.formats = [];
                $scope.themes = [];

                if($scope.search.support !== 'all'){

                    $mediaresaApi.getSupportCategories($scope.search.support).then(function (response) {
                        $scope.categories = response.data;
                    });

                    $mediaresaApi.getSupportFormats($scope.search.support).then(function (response) {
                        $scope.formats = response.data;
                    });

                    $mediaresaApi.getSupportThemes($scope.search.support).then(function (response) {
                        $scope.themes = response.data;
                    });
                }
            };

            $scope.$watch('search.support', function (newValue, oldValue) {
                if(newValue !== oldValue) {
                    $scope.updateSupport(false);
                }
            });

            $scope.updateSupport(true);

        }]);