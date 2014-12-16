var Application = angular.module('OpenAgenda', ['ngRoute', 'ngAnimate','ngResource', 'angularFileUpload','ng-breadcrumbs', 'xeditable',
    'ApplicationControllers','CommonFactories','CommonDirectives', 'TopBar','Dashboard', 'Menu','Meeting', 'Task', 'Setting', 'Http', 'ngSanitize',
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
                label: 'Meeting Übersicht'
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
                controller: 'MeetingExecuteCtrl',
                label: 'Meeting durchführen'
            }).
            when('/task', {
                label:'Aufgaben'
            }).
            when('/task/others', {
                templateUrl: '/template/task/index.html',
                controller: 'TaskIndexCtrl',
                label:'Aufgaben Anderer'
            }).
            when('/task/mine', {
                templateUrl: '/template/task/index.html',
                controller: 'TaskIndexCtrl',
                label:'Meine Aufgaben'
            }).
            when('/task/show/:taskId', {
                templateUrl: '/template/task/show.html',
                controller: 'Aufgabe bearbeiten'
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
                controller: 'userSettingCtrl',
                label:'Benachrichtigungseinstellung'
            }).
            when('/settings/user/profile', {
                templateUrl: '/template/setting/userProfile.html',
                controller: 'userProfileCtrl',
                label:'Profilverwaltung'
            }).
            otherwise({
                redirectTo: '/dashboard'
            });
    }]);
/*Required for xeditable Library*/
Application.run(function(editableOptions) {
    editableOptions.theme = 'bs3';
});


