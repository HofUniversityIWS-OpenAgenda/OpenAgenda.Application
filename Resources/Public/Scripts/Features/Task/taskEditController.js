/**
 * @class angular_module.Task
 * @memberOf angular_module
 * @description This Module contains the possibilities to edit a task
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("Task")
/**
 * @class angular_module.Task.TaskEditCtrl
 */
    /*TaskEditController is used to edit and save tasks*/
    .controller('TaskEditCtrl', ['$scope', '$rootScope', '$http', "TaskResourceHelper", "CommonHelperMethods", 'OpenAgenda.Data.Utility', '$modal', '$log',
        function ($scope, $rootScope, $http, TaskResourceHelper, CommonHelperMethods, oaUtility, $modal, $log) {
            console.log("Task Edit Controller loaded");
            /**
             * @function
             * @memberOf angular_module.Task.TaskEditCtrl
             * @description Function, which is used to open a modal instance window. Passes the selected Task to the modal window.
             * @param {string} [size] Size of modal window
             * @param {object} task Selected Task
             */
            $scope.open = function (size, task) {

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
                /**
                 * @function
                 * @memberOf angular_module.Task.TaskEditCtrl
                 * @description Function, which is fired when a modal instance window is closed. Reloads the Tasks, if it was successful.
                 * @param {string} string Possible close reason
                 * @param {object} task Selected Task
                 */
                modalInstance.close = function (string, task) {
                    $http.post('/task/'+ task.__identity +'/update.json', { task: oaUtility.jsonCast(task) }).
                        success(function(data, status, headers, config) {
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
    /**
     * @class angular_module.Task.TaskEditModalInstanceCtrl
     * @description This controller is used to handle the modal view to view and change a tasks state
     */
    .controller('TaskEditModalInstanceCtrl', function ($scope, $modalInstance, TaskResourceHelper, CommonHelperMethods, task) {
        TaskResourceHelper.getTaskDetail(task.__identity).get(function (task) {
            task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
            /**
             * @memberOf angular_module.Task.TaskEditModalInstanceCtrl
             */
            $scope.task = task;
        });
        /**
         * @function
         * @memberOf angular_module.Task.TaskEditModalInstanceCtrl
         * @description Closes a modal window
         */
        $scope.ok = function () {
            $modalInstance.close("OK", $scope.task);
        };
        /**
         * @function
         * @memberOf angular_module.Task.TaskEditModalInstanceCtrl
         * @description Closes a modal window.
         */
        $scope.cancel = function () {
            $modalInstance.dismiss('DISMISS');
        };

    });
