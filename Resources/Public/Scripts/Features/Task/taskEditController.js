/**
 * Created by Thomas on 08.12.14.
 */

angular.module("Task")
    .controller('TaskEditCtrl', ['$scope', '$rootScope', '$resource', "TaskResourceHelper", "CommonHelperMethods", '$modal', '$log',
        function ($scope, $rootScope, $resource, TaskResourceHelper, CommonHelperMethods, $modal, $log) {
            console.log("Task Edit Controller Loaded", $scope.task);

            $scope.open = function (size, identity) {

                var modalInstance = $modal.open({
                    templateUrl: '/template/task/edit.html',
                    controller: 'ModalInstanceCtrl',
                    size: size,
                    resolve: {
                        identity: function () {
                            return identity;
                        }
                    }
                });

                modalInstance.close = function (string) {
                    //Speichern, dann neu Laden
                    $scope.$parent.initTable();
                    console.log(string);
                    modalInstance.dismiss();
                };
            };
        }])
    .controller('ModalInstanceCtrl', function ($scope, $modalInstance, TaskResourceHelper, CommonHelperMethods, identity) {
        TaskResourceHelper.getTaskDetail(identity).get(function (task) {
            task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
            $scope.task = task;
            console.log('modalInstanceCtrl', task);
        });

        $scope.ok = function () {
            $modalInstance.close("OK", $scope.task);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('DISSMISS');
        };

    });
