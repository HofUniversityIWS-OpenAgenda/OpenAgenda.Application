/**
 * Main Module of OpenAgenda. Injects all needed modules.
 *
 * @module App
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 * @author Andreas Weber <andreas.weber@hof-university.de>
 */

/**
 * @description Main module for OpenAgenda. Its used to inject all needed Modules and to configure the App. See the example below, how the app configures the route-provider.
 * @var  Application
 *
 * @example Application.config(['$routeProvider',function($routeProvider) {
         $routeProvider.
            when('/dashboard', {
                templateUrl: '/template/dashboard/index.html',
                controller: 'DashboardCtrl',
                label: 'Dashboard'
            }).
            otherwise({
                redirectTo: '/dashboard'
            });
    }]);
 */
var Application = angular.module('OpenAgenda', ['ngRoute', 'ngAnimate','ngResource', 'angularFileUpload','ng-breadcrumbs', 'xeditable', 'autocomplete',
    'ApplicationControllers','CommonFactories','CommonDirectives', 'CommonServices', 'TopBar','Dashboard', 'Menu','Meeting', 'Task','OpenAgenda.Data', 'Setting', 'Http', 'ngSanitize',
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
                templateUrl: '/template/setting/Setting.html',
                controller: 'userSettingCtrl',
                label:'Benachrichtigungseinstellung'
            }).
            when('/settings/user/profile', {
                templateUrl: '/template/setting/Profile.html',
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

Application.run(function($rootScope, ModalDialog, Help) {
    $rootScope.help = Help;

});

