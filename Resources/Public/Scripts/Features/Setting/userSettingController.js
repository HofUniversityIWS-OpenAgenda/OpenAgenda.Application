/**
 * @author Andreas Weber <andreas.weber@hof-university.de>
 */

angular.module("Setting")
    .controller('userSettingCtrl', ['$scope', '$http', '$rootScope', '$routeParams', '$resource', "breadcrumbs",
        function($scope, $http, $rootScope, $routeParams, $resource, breadcrumbs){
            $scope.breadcrumbs = breadcrumbs;

        }]);