var Application = angular.module('OpenAgenda', ['ngRoute', 'ngResource', 'ng-breadcrumbs',
    'ApplicationControllers','CommonFactories', 'TopBar','Dashboard', 'Menu','Meeting', 'ngSanitize',
    'ui.calendar','ui.bootstrap']);

Application.config(['$routeProvider',
    function($routeProvider) {

        $routeProvider.
            when('/dashboard', {
                templateUrl: '/template/dashboard/index.html',
                controller: 'DashboardCtrl',
                label: 'Dashboard'
            }).
            when('/meetings', {
                templateUrl: '/template/meeting/index.html',
                controller: 'MeetingIndexCtrl',
                label: 'Meetings'
            }).
            when('/meetings/new', {
                templateUrl: '/template/meeting/new.html'//,
                //controller: 'MeetingDetailCtrl'
            }).
            when('/meetings/show/:meetingId', {
                templateUrl: '/template/meeting/show.html',
                controller: 'MeetingDetailCtrl'
            }).
            when('/tasks', {
                templateUrl: '/template/task/index.html',
                controller: 'TaskCtrl'
            }).
            when('/tasks/others', {
                templateUrl: '/template/task/index.html',
                controller: 'TaskCtrl'
            }).
            when('/calendar', {
                templateUrl: '/template/calendar/index.html',
                controller: 'CalendarCtrl'
            }).
            when('/calendar/show/:eventId', {
                templateUrl: '/template/calendar/show.html',
                controller: 'CalendarDetailCtrl'
            }).
            when('/settings/user/', {
                templateUrl: '/template/setting/index.html',
                controller: 'SettingCtrl'
            }).
            when('/settings/user/profile', {
                templateUrl: '/template/setting/index.html',
                controller: 'SettingCtrl'
            }).
            otherwise({
                redirectTo: '/dashboard'
            });
    }]);