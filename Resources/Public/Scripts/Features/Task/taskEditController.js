/**
 * Created by Thomas on 08.12.14.
 */

angular.module("Task")
    .controller('TaskEditCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs", "TaskResourceHelper","CommonHelperMethods",'$modal', '$log',
        function ($scope, $rootScope, $http, breadcrumbs, TaskResourceHelper, CommonHelperMethods, $modal, $log) {
            console.log("Task Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

            $scope.items = ['item1', 'item2', 'item3'];
//Task Detail abholen
            $scope.open = function (size, identity) {


                var modalInstance = $modal.open({
                    templateUrl: '/template/task/edit.html',
                    controller: 'ModalInstanceCtrl',
                    size: size,
                    resolve: {
                        items: function () {
                            return $scope.items;
                        }
                    }
                });

                modalInstance.result.then(function (selectedItem) {
                    $scope.selected = selectedItem;
                }, function () {
                    $log.info('Modal dismissed at: ' + new Date());
                });
            };


        }])
    .controller('ModalInstanceCtrl', function ($scope, $modalInstance, items) {

        $scope.items = items;
        $scope.selected = {
            item: $scope.items[0]
        };

        $scope.ok = function () {
            $modalInstance.close($scope.selected.item);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    })


;
