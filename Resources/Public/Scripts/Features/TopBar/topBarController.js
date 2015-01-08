/**
 * @class angular_module.TopBar
 * @memberOf angular_module
 * @description This Module contains the controller for the topbar
 * Its used to load personal notifications e.g. initiations
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("TopBar", [])
/**
 * @class angular_module.TopBar.TopBarCtrl
 */
    .controller('TobBarCtrl', ['$scope', '$rootScope', '$http', 'ModalDialog', 'CommonResourceHelper',
        function ($scope, $rootScope, $http, ModalDialog, CommonResourceHelper) {
            console.log("Topbar Controller Loaded");

            /**
             * @memberOf angular_module.TopBar.TopBarCtrl
             * @description Globally stored notifications
             */
            $rootScope.notifications = [];
            //getNotifications from Server
            /**
             * @function
             * @memberOf angular_module.TopBar.TopBarCtrl
             * @description Reload all personal Notifications
             */
            function reloadNotifications() {
                $scope.personalInfos = CommonResourceHelper.getPersonalInfos().get(function () {
                    $rootScope.notifications = $scope.personalInfos.openInvitations;
                });
            };
            reloadNotifications();

            $scope.test = function () {
                console.log($scope.getNotifications());
            };
            /**
             * @function
             * @memberOf angular_module.TopBar.TopBarCtrl
             */
            $scope.getNotifications = function () {
                return $rootScope.notifications;
            };
            /*
            * If Heartbeat is activated, a message is shown when server is not reachable
            * */
             $rootScope.$watch('online', function (newValue, oldValue) {
                if (!newValue && typeof newValue != 'undefined') {
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

            /**
             * @function
             * @memberOf angular_module.TopBar.TopBarCtrl
             * @description Is used to accept a certain Meeting. Displays a modal dialog after success or error.
             * @param {string} id ID of Meeting
             */
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

            /**
             * @function
             * @memberOf angular_module.TopBar.TopBarCtrl
             * @description Is used to decline a certain Meeting. Displays a modal dialog after success or error.
             * @param {string} id ID of Meeting
             */
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
                    });
            };
        }]);