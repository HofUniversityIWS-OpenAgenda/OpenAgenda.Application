/**
 * This Module contains the  possibilities to edit a task
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */


angular.module("Task")
    /*TaskEditController is used to edit and save tasks*/
    .controller('TaskEditCtrl', ['$scope', '$rootScope', '$http', "TaskResourceHelper", "CommonHelperMethods", '$modal', '$log',
        function ($scope, $rootScope, $http, TaskResourceHelper, CommonHelperMethods, $modal, $log) {
            console.log("Task Edit Controller Loaded");

            $scope.open = function (size, task, meetingName, assignee) {

                var modalInstance = $modal.open({
                    templateUrl: '/template/task/edit.html',
                    controller: 'TaskEditModalInstanceCtrl',
                    size: size,
                    resolve: {
                        task: function() {
                            return task;
                        },
                        meetingName: function() {
                            return meetingName;
                        },
                        assignee: function() {
                            return assignee;
                        }
                    }
                });

                modalInstance.close = function (string, task) {

                    //Hier ist der Standard speicher versuch
                    //Es heist zwar das es erfolgreich gespcihert wurde, aber es wird nicht gespeichert

                    $http.post('/task/'+ task.__identity +'/update.json', task).
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
    .controller('TaskEditModalInstanceCtrl', function ($scope, $modalInstance, TaskResourceHelper, CommonHelperMethods, task, meetingName, assignee) {
        $scope.assignee = assignee;
        $scope.meetingName = meetingName;

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
