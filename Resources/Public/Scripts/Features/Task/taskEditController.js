/**
 * Created by Thomas on 08.12.14.
 */

angular.module("Task")
    .controller('TaskEditCtrl', ['$scope', '$rootScope', '$resource', "TaskResourceHelper", "CommonHelperMethods", '$modal', '$log',
        function ($scope, $rootScope, $resource, TaskResourceHelper, CommonHelperMethods, $modal, $log) {
            console.log("Task Edit Controller Loaded");


            $scope.open = function (size, identity) {
                console.log(identity);

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

                modalInstance.result.then(function (string) {
                    $scope.selected = string;
                }, function () {
                    $log.info('Modal dismissed at: ' + new Date());
                });
            };


        }])
    .controller('ModalInstanceCtrl', function ($scope, $modalInstance, TaskResourceHelper, CommonHelperMethods, identity) {
       TaskResourceHelper.getTaskDetail(identity).get(function (task) {
            task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
           $scope.task = task;
        });
        $scope.ok = function () {
            console.log("OK, SAVE");
            $modalInstance.close("OK");
        };

        $scope.cancel = function () {
            console.log("DISMISS");
            $modalInstance.dismiss('cancel');
        };
    })


;
