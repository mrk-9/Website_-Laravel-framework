angular.module('mediaresa-app')
    .controller('AdPlacementBuyModalStep5Ctrl', ['$scope', '$mediaresaApi',
        function ($scope, $mediaresaApi) {

            $scope.templates = [];
            $mediaresaApi.getTemplates().then(function(response) {
                $scope.templates = response.data;
                $scope.setPage();
            });

            $scope.templatesPerPage = 3;
            $scope.currentPage = 1;

            $scope.setPage = function(){
                 $scope.page = $scope.templates.filter(function(obj, i) {
                    return i >= ($scope.currentPage - 1) * $scope.templatesPerPage && i < ((($scope.currentPage - 1) * $scope.templatesPerPage) + $scope.templatesPerPage);
                });
            }

        }]);