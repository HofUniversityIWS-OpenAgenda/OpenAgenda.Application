/**
 * This Module contains the Menu
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("Menu", [])
    .controller('MenuCtrl', ['$scope','$rootScope', "$sce", "$location",
        function ($scope, $rootScope, $sce, $location) {
            console.log("Menu Controller Loaded");

            /*Should be used to add a Toolbar
            * Not used in this version
            * */
            $scope.toolBar;
            $rootScope.changeToolBar = function (htmlCode) {
                $scope.toolBar = htmlCode;
            };

            $scope.insertToolbar = function() {
                return $sce.trustAsHtml($scope.toolBar)
            }

            /*Set the active Menu item*/
            $scope.isActive = function (viewLocation) {
                var active = (viewLocation === $location.path());
                return active;
            };
        }]);