/**
 * @class angular_module.Setting
 * @memberOf angular_module
 *
 * @author Andreas Weber <andreas.weber@hof-university.de>
 */

angular.module("Setting")
/**
 * @class angular_module.Setting.UserSettingCtrl
 */
    .controller('userSettingCtrl', ['$scope', '$http', '$rootScope', '$routeParams', '$resource', "breadcrumbs",
        function($scope, $http, $rootScope, $routeParams, $resource, breadcrumbs){
            $scope.breadcrumbs = breadcrumbs;

        }]);