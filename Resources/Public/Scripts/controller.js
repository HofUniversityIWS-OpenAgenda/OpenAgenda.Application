/**
 *
 * @module ApplicationControllers
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 * @author Andreas Weber <andreas.weber@hof-university.de>
 * @deprecated It's just an old module for testing. It's not used anymore.
 */

/**
 * @description Old controller module for OpenAgenda.
 * @var  ApplicationControllers
 * @deprecated Just an old module for testing in the beginning. It's not used anymore.
 */
var ApplicationControllers = angular.module('ApplicationControllers', []);

ApplicationControllers.controller('TaskCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.get('/openagenda.application/task/index.json').success(function(data) {
            $scope.meetings = data;
        });
        $scope.orderProp = 'dueDate';
    }]);

ApplicationControllers.controller('CalendarCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.get('/openagenda.application/Calendar/index.json').success(function(data) {
            $scope.events = data;
        });
        $scope.orderProp = 'startDateTime';
    }]);
ApplicationControllers.controller('CalendarDetailCtrl', ['$scope', '$routeParams',
    function($scope, $routeParams) {
        $scope.event = $routeParams.eventId;
    }]);

ApplicationControllers.controller('SettingCtrl', ['$scope', '$http',
    function ($scope, $http) {
        console.log("Settings Controller Loaded");
        $http.get('/openagenda.application/setting/index.json').success(function(data) {
            $scope.setting = data;
        });
    }]);

