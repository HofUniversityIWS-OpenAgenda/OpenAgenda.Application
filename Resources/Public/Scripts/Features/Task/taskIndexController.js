
/**
 * @memberOf angular_module
 * @description This Module contains the list of all tasks.
 * Its possible to either view only personal tasks or to view all tasks from others
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */


angular.module("Task", [])
/**
 * @class angular_module.Task.TaskIndexCtrl
 */
    .controller('TaskIndexCtrl', ['$scope', '$rootScope', '$location', '$resource', "breadcrumbs", "MeetingResourceHelper", "TaskResourceHelper", "CommonHelperMethods",
        function ($scope, $rootScope, $location, $resource, breadcrumbs, MeetingResourceHelper, TaskResourceHelper, CommonHelperMethods) {
            console.log("Task Index Controller Loaded")
            /**@memberOf angular_module.Task.TaskIndexCtrl*/
            $scope.breadcrumbs = breadcrumbs;
            /**@memberOf angular_module.Task.TaskIndexCtrl*/
            $scope.loading = true;
            /**
             * @function
             * @memberOf angular_module.Task.TaskIndexCtrl
             * @description Fetches all Tasks from backend and reload Tasks.
             */
            $scope.reloadTasks = function () {
                $scope.taskList = [];

                    TaskResourceHelper.getTaskList($scope.showAllTasks).query(function (data) {
                        angular.forEach(data, function (task) {
                            task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
                        });
                        $scope.taskList = data;
                        $scope.showAllTasksCheckboxDisabled = false;
                        $scope.loading= false;

                    }, function (err) {
                        alert('request failed');
                    });


            };
            $scope.showAllTasks = false;
            if ($location.url() == "/task/others")
                $scope.showAllTasks = true;

            $scope.$watch("showAllTasks", function (newVal) {
                $scope.showAllTasksCheckboxDisabled = true;
                $scope.reloadTasks();
            })

            /**
             * @function
             * @memberOf angular_module.Task.TaskIndexCtrl
             * @description Get the name of selected Task from backend. Sets the name of selected Task
             * @param {object} task Selected Task
             */
            function getMeetingName(task) {
                MeetingResourceHelper.getMeetingDetail(task.meeting).get(function (data) {
                    task.meeting = data.title
                });
            };
        }]);

