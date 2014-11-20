var Application = angular.module('Application', ['Application', 'ngRoute', 'ApplicationControllers']);

Application.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/dashboard', {
                templateUrl: 'Resources/Private/Templates/Dashboard/Index.html',
                controller: 'DashboardCtrl'
            }).
            when('/meeting', {
                templateUrl: 'Resources/Private/Templates/Meeting/Index.html',
                controller: 'MeetingCtrl'
            }).
            when('/meeting/show/:meetingId', {
                templateUrl: 'Resources/Private/Templates/MeetingDetail/Index.html',
                controller: 'MeetingDetailCtrl'
            }).
            when('/task', {
                templateUrl: 'Resources/Private/Templates/Task/Index.html',
                controller: 'TaskCtrl'
            }).
            when('/calendar', {
                templateUrl: 'Resources/Private/Templates/Calendar/Index.html',
                controller: 'CalendarCtrl'
            }).
            when('/calendar/show/:eventId', {
                templateUrl: 'Resources/Private/Templates/CalendarDetail/Index.html',
                controller: 'CalendarDetailCtrl'
            }).
            when('/setting', {
                templateUrl: 'Resources/Private/Templates/Setting/Index.html',
                controller: 'SettingCtrl'
            }).
            otherwise({
                redirectTo: '/dashboard'
            });
    }]);