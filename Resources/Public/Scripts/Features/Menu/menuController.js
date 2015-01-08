/**
 * @class angular_module.Menu
 * @memberOf angular_module
 * @description This Module contains the Menu
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("Menu", [])
/**
 * @class angular_module.Menu.MenuCtrl
 */
    .controller('MenuCtrl', ['$scope','$rootScope', "$sce", "$location",
        function ($scope, $rootScope, $sce, $location) {
            console.log("Menu Controller Loaded");

            /**
             * @memberOf angular_module.Menu.MenuCtrl
             * @description Should be used to add a Toolbar.
             * Not used in this version
             */
            $scope.toolBar;

            /**
             * @function
             * @memberOf angular_module.Menu.MenuCtrl
             * @description Change the HTML in the left Toolbar
             */
            $rootScope.changeToolBar = function (htmlCode) {
                $scope.toolBar = htmlCode;
            };
            /**
             * @function
             * @memberOf angular_module.Menu.MenuCtrl
             * @description Insert new Toolbar as HTML in the left Toolbar
             */
            $scope.insertToolbar = function() {
                return $sce.trustAsHtml($scope.toolBar)
            }

            /**
             * @function
             * @memberOf angular_module.Menu.MenuCtrl
             * @description Set the active Menu item
             */
            $scope.isActive = function (viewLocation) {
                var active = (viewLocation === $location.path());
                return active;
            };
        }]);