/**
 * This Module contains the list of all tasks
 * Its possible to either view only personal tasks or to view all tasks from others
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */


angular.module("Task", [])
    .controller('TaskIndexCtrl', ['$scope', '$rootScope', '$location', '$resource', "breadcrumbs", "MeetingResourceHelper", "TaskResourceHelper", "CommonHelperMethods",
        function ($scope, $rootScope, $location, $resource, breadcrumbs, MeetingResourceHelper, TaskResourceHelper, CommonHelperMethods) {
            console.log("Task Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

            $scope.loading = true;
            $scope.reloadTasks = function () {
                $scope.taskList = [];
                if (!$scope.showAllTasks) {
                    TaskResourceHelper.getTaskList().query(function (data) {
                        console.log('success, got taskList: ', data);
                        angular.forEach(data, function (task) {
                            task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
                            getMeetingName(task);
                        });
                        $scope.taskList = data;
                        $scope.showAllTasksCheckboxDisabled = false;
                        $scope.loading= false;

                    }, function (err) {
                        alert('request failed');
                    });
                }
                else {
                    console.log("LOAD OTHERS");
                    $scope.loading= false;
                  //TODO: No rest interface for personal or others taks
                }

            };
            $scope.showAllTasks = false;
            if ($location.url() == "/task/others")
                $scope.showAllTasks = true;

            $scope.reloadTasks();

            $scope.$watch("showAllTasks", function (newVal) {
                $scope.showAllTasksCheckboxDisabled = true;
                console.log($scope.showAllTasks);
                $scope.reloadTasks();
            })

            function getMeetingName(task) {

                MeetingResourceHelper.getMeetingDetail(task.meeting).get(function (data) {
                    task.meeting = data.title
                });
            };
        }]);

