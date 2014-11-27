/**
 * Created by Thomas on 27.11.14.
 */
angular.module("TopBar", [])
    .controller('TobBarCtrl', ['$scope','$rootScope',
        function ($scope, $rootScope) {
            console.log("TopBar Controller Loaded");

            /*$http.get('/openagenda.application/dashboard/index.json').success(function(data) {
             $scope.data = data;
             });*/

            $rootScope.notifications = [1,2,3,4];
            //getNotifications from Server


            $scope.test = function (){
                console.log($scope.getNotifications());
            }

            $scope.getNotifications= function() {
                return $rootScope.notifications;
            };
        }]);