/**
 * Created by Thomas on 08.12.14.
 */

angular.module("Task", [])
    .controller('TaskIndexCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs", "MeetingResourceHelper", "TaskResourceHelper","CommonHelperMethods",'$modal', '$log',
        function ($scope, $rootScope, $http, breadcrumbs, MeetingResourceHelper, TaskResourceHelper, CommonHelperMethods, $modal, $log) {
            console.log("Task Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

             TaskResourceHelper.getTaskList().query(function (data) {
                console.log('success, got taskList: ', data);
                angular.forEach(data, function (task) {
                    task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
                    $scope.getMeetingName(task);
                });
                 $scope.taskList = data;
            }, function (err) {
                alert('request failed');
            });

            $scope.getMeetingName = function (task) {

                MeetingResourceHelper.getMeetingDetail(task.meeting).get(function (data) {
                    task.meeting = data.title;
                });

            };
        }])
    ;

