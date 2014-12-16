/**
 * This Module contains the controller for the topbar
 *
 * Its used to load personal notifications e.g. initiations
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("TopBar", [])
    .controller('TobBarCtrl', ['$scope','$rootScope',
        function ($scope, $rootScope) {
            console.log("TopBar Controller Loaded");

            /*$http.get('/openagenda.application/dashboard/index.json').success(function(data) {
             $scope.data = data;
             });*/

            //TODO: No rest interface to get all personal notifications

            $rootScope.notifications = [1,2,3,4];
            //getNotifications from Server

            $scope.test = function (){
                console.log($scope.getNotifications());
            }

            $scope.getNotifications= function() {
                return $rootScope.notifications;
            };
        }]);