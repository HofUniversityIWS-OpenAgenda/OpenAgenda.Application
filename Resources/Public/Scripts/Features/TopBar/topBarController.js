/**
 * This Module contains the controller for the topbar
 *
 * Its used to load personal notifications e.g. initiations
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("TopBar", [])
    .controller('TobBarCtrl', ['$scope','$rootScope','ModalDialog',
        function ($scope, $rootScope, ModalDialog) {
            console.log("Topbar Controller Loaded");

            /*$http.get('/openagenda.apssplication/dashboard/index.json').success(function(data) {
             $scope.data = data;
             });*/

            //TODO: No rest interface to get all personal notifications

            $rootScope.notifications = [1,2,3,4];
            //getNotifications from Server

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
        }]);