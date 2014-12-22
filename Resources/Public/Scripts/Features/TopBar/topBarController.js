/**
 * This Module contains the controller for the topbar
 *
 * Its used to load personal notifications e.g. initiations
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("TopBar", [])
    .controller('TobBarCtrl', ['$scope','$rootScope','$http','ModalDialog','CommonResourceHelper',
        function ($scope, $rootScope, $http, ModalDialog, CommonResourceHelper) {
            console.log("Topbar Controller Loaded");

            /*$http.get('/openagenda.apssplication/dashboard/index.json').success(function(data) {
             $scope.data = data;
             });*/

            //TODO: No rest interface to get all personal notifications

            $rootScope.notifications = [];
            //getNotifications from Server
           function reloadNotifications () {
               $scope.personalInfos = CommonResourceHelper.getPersonalInfos().get(function () {
                   console.log("PER", $scope.personalInfos);
                   $rootScope.notifications = $scope.personalInfos.openInvitations;
               });
           };
            reloadNotifications();

            $scope.test = function (){
                console.log($scope.getNotifications());
            };

            $scope.getNotifications= function() {
                return $rootScope.notifications;
            };

            $rootScope.$watch('online', function(newValue, oldValue) {
                if(!newValue && typeof newValue != 'undefined')
                {
                    console.log(newValue, oldValue);
                    var modalDefaults = {
                        templateUrl: '/template/modaldialog/warning.html'
                    };
                    var modalOptions = {
                        headerText: 'Hinweis',
                        bodyText: 'Die Verbindung zum Server ist verloren gegangen!'
                    };
                    ModalDialog.showModal(modalDefaults, modalOptions);
                }
            });

            $scope.acceptInvitation = function (id) {
                $http.get('/invitation/accept.json?invitation[__identity]=' + id, {proxy: true}).
                    success(function (data, status, headers, config) {
                        console.log("SUCCESS");
                        var modalOptions = {
                            headerText: 'Erfolg',
                            bodyText: 'Meeting-Einleitung erfolgreich akzeptiert!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/success.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                        reloadNotifications();
                    }).error(function (data, status, headers, config) {
                       console.log("ERROR");
                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Es ist ein Fehler aufgetreten!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                        reloadNotifications();
                    });
            };

            $scope.declineInvitation = function (id) {
                $http.get('/invitation/accept.json?invitation[__identity]=' + id, {proxy: true}).
                    success(function (data, status, headers, config) {
                        console.log("SUCCESS");
                        var modalOptions = {
                            headerText: 'Erfolg',
                            bodyText: 'Meeting-Einleitung erfolgreich abgelehnt!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/success.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    }).error(function (data, status, headers, config) {
                        console.log("ERROR");
                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Es ist ein Fehler aufgetreten!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    });
            };
        }]);