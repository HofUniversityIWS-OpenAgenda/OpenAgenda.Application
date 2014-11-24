var Application = angular.module('OpenAgenda', ['ngRoute', 'ApplicationControllers']);

Application.config(['$routeProvider',
    function($routeProvider) {

        $routeProvider.
            when('/dashboard', {
                templateUrl: '/openagenda.application/dashboard/index',
                controller: 'DashboardCtrl'
            }).
            when('/meetingABC', {
                templateUrl: '/openagenda.application/meeting/index',
                controller: 'MeetingCtrl'
            }).
            when('/meeting/show/:meetingId', {
                templateUrl: '/openagenda.application/meeting/show',
                controller: 'MeetingDetailCtrl'
            }).
            when('/task', {
                templateUrl: '/openagenda.application/task/index',
                controller: 'TaskCtrl'
            }).
            when('/calendar', {
                templateUrl: 'openagenda.application/calendar/index',
                controller: 'CalendarCtrl'
            }).
            when('/calendar/show/:eventId', {
                templateUrl: 'openagenda.application/calendar/show',
                controller: 'CalendarDetailCtrl'
            }).
            when('/setting', {
                templateUrl: '/openagenda.application/setting/index',
                controller: 'SettingCtrl'
            }).
            otherwise({
                redirectTo: '/login'
            });
    }]);