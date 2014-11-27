/**
 * Created by Thomas on 27.11.14.
 */

angular.module("Menu", [])
    .controller('MenuCtrl', ['$scope','$rootScope', "$sce",
        function ($scope, $rootScope, $sce) {
            console.log("Menu Controller Loaded");

            $rootScope.toolBarHTML;

            $scope.t;


            $rootScope.changeToolBar = function (htmlCode) {
                $scope.t = htmlCode;
            };

            $scope.insertToolbar = function() {
                return $sce.trustAsHtml($scope.t)
            }

        }]);