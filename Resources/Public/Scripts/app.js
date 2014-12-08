var Application = angular.module('OpenAgenda', ['ngRoute', 'ngResource', 'angularFileUpload','ng-breadcrumbs', 'xeditable',
    'ApplicationControllers','CommonFactories','CommonDirectives', 'TopBar','Dashboard', 'Menu','Meeting', 'Task', 'ngSanitize',
    'ui.calendar','ui.bootstrap']);

Application.config(['$routeProvider',
    function($routeProvider) {

        $routeProvider.
            when('/dashboard', {
                templateUrl: '/template/dashboard/index.html',
                controller: 'DashboardCtrl',
                label: 'Dashboard'
            }).
            when('/meeting', {
                templateUrl: '/template/meeting/index.html',
                controller: 'MeetingIndexCtrl',
                label: 'Meetings'
            }).
            when('/meeting/new', {
                templateUrl: '/template/meeting/edit.html',
                controller: 'MeetingEditCtrl',
                label: 'Meeting anlegen'
            }).
            when('/meeting/show/:meetingId', {
                templateUrl: '/template/meeting/edit.html',
                controller: 'MeetingEditCtrl',
                label: 'Meeting bearbeiten'
            }).
            when('/meeting/start/:meetingId', {
                templateUrl: '/template/meeting/Execute.html',
                controller: 'MeetingExecuteCtrl'
            }).
            when('/task', {
                templateUrl: '/template/task/index.html',
                controller: 'TaskIndexCtrl',
                label:'Tasks'
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


