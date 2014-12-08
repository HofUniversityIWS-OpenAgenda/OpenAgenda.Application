/**
 * Created by Thomas on 08.12.14.
 */

angular.module("Task", [])
    .controller('TaskIndexCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs", "TaskResourceHelper","CommonHelperMethods",
        function ($scope, $rootScope, $http, breadcrumbs, TaskResourceHelper, CommonHelperMethods) {
            console.log("Task Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

            $scope.taskList = TaskResourceHelper.getTaskList().query(function (data) {
                console.log('success, got taskList: ', $scope.taskList);

            }, function (err) {
                alert('request failed');
            });



        }]);
