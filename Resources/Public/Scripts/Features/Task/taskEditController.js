/**
 * This Module contains the  possibilities to edit a task
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */


angular.module("Task")
    /*TaskEditController is used to edit and save tasks*/
    .controller('TaskEditCtrl', ['$scope', '$rootScope', '$http', "TaskResourceHelper", "CommonHelperMethods", 'OpenAgenda.Data.Utility', '$modal', '$log',
        function ($scope, $rootScope, $http, TaskResourceHelper, CommonHelperMethods, oaUtility, $modal, $log) {
            console.log("Task Edit Controller Loaded");

            $scope.open = function (size, task, meetingName, assignee) {

                var modalInstance = $modal.open({
                    templateUrl: '/template/task/edit.html',
                    controller: 'TaskEditModalInstanceCtrl',
                    size: size,
                    resolve: {
                        task: function() {
                            return task;
                        }
                    }
                });

                modalInstance.close = function (string, task) {
                    $http.post('/task/'+ task.__identity +'/update.json', { task: oaUtility.jsonCast(task) }).
                        success(function(data, status, headers, config) {
                            console.log("SUCCESSFULLY SAVED TASK");
                            $scope.$parent.reloadTasks();
                        }).
                        error(function(data, status, headers, config) {
                            // called asynchronously if an error occurs
                            // or server returns response with an error status.
                        });
                    modalInstance.dismiss();
                };
            };
        }])
    /*This controller is used to handle the modal view to view and change a tasks state*/
    .controller('TaskEditModalInstanceCtrl', function ($scope, $modalInstance, TaskResourceHelper, CommonHelperMethods, task) {
        TaskResourceHelper.getTaskDetail(task.__identity).get(function (task) {
            task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
            $scope.task = task;
        });

        $scope.ok = function () {
            $modalInstance.close("OK", $scope.task);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('DISMISS');
        };

    });
