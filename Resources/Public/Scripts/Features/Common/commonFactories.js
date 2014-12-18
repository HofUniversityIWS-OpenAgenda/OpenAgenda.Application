/**
 * This Module defines custom Factories to use in the whole application
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("CommonFactories", [])
    .factory('CommonHelperMethods', function () {
        return {
            // @deprecated Not used anymore
            getDateFromJSONString: function (string) {
                return new Date(string);
            }
        };
    })
    .factory('CommonResourceHelper', ['$resource', function ($resource) {
        return {
            getPersonalInfos: function () {
                return $resource('dashboard/index.json', {}, {
                    get: {method: 'GET'}
                });
            }
        };

    }])
    .factory('MeetingResourceHelper', ['$resource', function ($resource) {
        return {
            getMeetingList: function () {
                return $resource('meeting/list.json', {}, {
                    query: {method: 'GET', isArray: true}
                });
            },
            getMeetingDetail: function (id) {
                return $resource('meeting/:meetingId/show.json', {meetingId: id}, {
                    get: {method: 'GET'}
                });
            }
        };

    }])
    .factory('TaskResourceHelper', ['$resource', function ($resource) {
        return {
            getTaskList: function () {
                return $resource('task/list.json', {}, {
                    query: {method: 'GET', isArray: true}
                });
            },
            getTaskDetail: function (id) {
                return $resource('task/:taskId/show.json', {taskId: id}, {
                    get: {method: 'GET'}
                });
            }
        };
    }])
    .service('ModalDialog', ['$modal',
        function ($modal) {

            var modalDefaults = {
                backdrop: true,
                keyboard: true,
                modalFade: true,
                templateUrl: '/template/modaldialog/index.html'

            };

            var modalOptions = {
                closeButtonText: 'Close',
                actionButtonText: 'OK',
                headerText: 'HEADER',
                bodyText: 'BODY'
            };


            this.showModal = function (customModalDefaults, customModalOptions) {
                if (!customModalDefaults) customModalDefaults = {};
                customModalDefaults.backdrop = 'static';
                return this.show(customModalDefaults, customModalOptions);
            };

            this.show = function (customModalDefaults, customModalOptions) {
                var tempModalDefaults = {};
                var tempModalOptions = {};

                angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);

                angular.extend(tempModalOptions, modalOptions, customModalOptions);

                if (!tempModalDefaults.controller) {
                    tempModalDefaults.controller = function ($scope, $modalInstance) {
                        $scope.modalOptions = tempModalOptions;
                        $scope.modalOptions.ok = function (result) {
                            $modalInstance.close(result);
                        };
                        $scope.modalOptions.close = function (result) {
                            $modalInstance.dismiss('cancel');
                        };
                    }
                }

                return $modal.open(tempModalDefaults).result;
            };

        }]);

