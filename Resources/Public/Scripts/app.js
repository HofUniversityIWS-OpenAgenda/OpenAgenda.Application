var Application = angular.module('OpenAgenda', ['ngRoute', 'ngResource', 'ng-breadcrumbs', 'ApplicationControllers','CommonFactories', 'TopBar','Dashboard', 'Menu', 'ngSanitize']);

Application.config(['$routeProvider',
    function($routeProvider) {

        $routeProvider.
            when('/dashboard', {
                templateUrl: '/openagenda.application/dashboard/index',
                controller: 'DashboardCtrl',
                label: 'Dashboard'
            }).
            when('/meetings', {
                templateUrl: '/openagenda.application/meeting/index',
                controller: 'MeetingCtrl',
                label: 'Meetings'
            }).
            when('/meetings/new', {
                templateUrl: '/openagenda.application/meeting/new'//,
                //controller: 'MeetingDetailCtrl'
            }).
            when('/meetings/show/:meetingId', {
                templateUrl: '/openagenda.application/meeting/show',
                controller: 'MeetingDetailCtrl'
            }).
            when('/tasks', {
                templateUrl: '/openagenda.application/task/index',
                controller: 'TaskCtrl'
            }).
            when('/tasks/others', {
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
            when('/settings/user/', {
                templateUrl: '/openagenda.application/setting/index',
                controller: 'SettingCtrl'
            }).
            when('/settings/user/profile', {
                templateUrl: '/openagenda.application/setting/index',
                controller: 'SettingCtrl'
            }).
            otherwise({
                redirectTo: '/dashboard'
            });
    }]);