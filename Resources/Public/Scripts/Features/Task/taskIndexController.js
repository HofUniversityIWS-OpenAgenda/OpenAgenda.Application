/**
 * Created by Thomas on 08.12.14.
 */

angular.module("Task", [])
    .controller('TaskIndexCtrl', ['$scope', '$rootScope', '$location', '$resource', "breadcrumbs", "MeetingResourceHelper", "TaskResourceHelper", "CommonHelperMethods",
        function ($scope, $rootScope, $location, $resource, breadcrumbs, MeetingResourceHelper, TaskResourceHelper, CommonHelperMethods) {
            console.log("Task Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

            $scope.initTable = function () {
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

                    }, function (err) {
                        alert('request failed');
                    });
                }
                else {
                    $scope.showAllTasksCheckboxDisabled = false;

                }

            };
            $scope.showAllTasks = false;
            if ($location.url() == "/task/others")
                $scope.showAllTasks = true;

            $scope.initTable();


            $scope.$watch("showAllTasks", function (newVal) {
                $scope.showAllTasksCheckboxDisabled = true;
                console.log($scope.showAllTasks);
                $scope.initTable();
            })


            function getMeetingName(task) {

                MeetingResourceHelper.getMeetingDetail(task.meeting).get(function (data) {
                    task.meeting = data.title
                });
            };
        }]);

