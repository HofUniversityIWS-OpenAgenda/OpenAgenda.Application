/**
 * Created by Andi on 16.12.14.
 */

angular.module("Setting")
    .controller('userSettingCtrl', ['$scope', '$http', '$rootScope', '$routeParams', '$resource', "breadcrumbs",
        function($scope, $http, $rootScope, $routeParams, $resource, breadcrumbs){
            $scope.breadcrumbs = breadcrumbs;

        }]);