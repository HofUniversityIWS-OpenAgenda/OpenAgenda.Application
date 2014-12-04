var Application = angular.module('OpenAgenda', ['ngRoute', 'ngResource', 'angularFileUpload','ng-breadcrumbs', 'xeditable',
    'ApplicationControllers','CommonFactories','CommonDirectives', 'TopBar','Dashboard', 'Menu','Meeting', 'ngSanitize',
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
                templateUrl: '/template/meeting/new.html',
                controller: 'MeetingCreateCtrl',
                label: 'Meeting anlegen'
            }).
            when('/meeting/show/:meetingId', {
                templateUrl: '/template/meeting/show.html',
                controller: 'MeetingShowCtrl'
            }).
            when('/meeting/start/:meetingId', {
                templateUrl: '/template/meeting/Execute.html',
                controller: 'MeetingExecuteCtrl'
            }).
            when('/tasks', {
                templateUrl: '/template/task/index.html',
                controller: 'TaskCtrl'
            }).
            when('/task/show/:taskId', {
                templateUrl: '/template/task/show.html',
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
            when('/settings/user', {
                templateUrl: '/template/setting/userSetting.html',
                controller: 'SettingCtrl'
            }).
            when('/settings/user/profile', {
                templateUrl: '/template/setting/userProfile.html',
                controller: 'SettingCtrl'
            }).
            otherwise({
                redirectTo: '/dashboard'
            });
    }]);

Application.run(function(editableOptions) {
    editableOptions.theme = 'bs3';
});


