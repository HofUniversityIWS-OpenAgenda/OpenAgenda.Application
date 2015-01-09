/**
 * @module CommonFactories
 *
 * @description This Module defines custom Factories to use in the whole application
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("CommonFactories", [])
    /**
     * @description Converts the date string, which is delivered from backend.
     * @author Thomas Winkler <thomas.winkler@hof-university.de>
     * @function factory
     * @param {string} "CommonHelperMethods" Identifier
     * @param {function} function()
     * @deprecated Not used anymore. Backend optimized!
     * @returns {function} getDateFromJSONString
     */
    .factory('CommonHelperMethods', function () {
        return {
            getDateFromJSONString: function (string) {
                return new Date(string);
            }
        };
    })
    /**
     * @description Gets the user's personal infos as an $resource object.
     * @author Thomas Winkler <thomas.winkler@hof-university.de>
     * @function factory
     * @param {string} "CommonResourceHelper" Identifier
     * @param {string} '$resource'
     * @returns {function} getPersonalInfos
     *
     * @example  $scope.personalInfos = CommonResourceHelper.getPersonalInfos().get(function () {
                $scope.currentUser = $scope.personalInfos.person.name.firstName;
            });
     */
    .factory('CommonResourceHelper', ['$resource', function ($resource) {
        return {
            getPersonalInfos: function () {
                return $resource('dashboard/index.json', {}, {
                    get: {method: 'GET'}
                });
            }
        };

    }])
    /**
     * @description Gets the all Meetings or one specific Meeting by ID
     *
     * @author Thomas Winkler <thomas.winkler@hof-university.de>
     * @function factory
     * @param {string} "MeetingResourceHelper" Identifier
     * @param {string} '$resource'
     * @param {function} function($resource)
     * @returns {function} getMeetingList
     * @returns {function} getMeetingDetail
     */
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
    /**
     * @description Gets mine or all others Tasks. Also returns details of a specific Task by ID
     *
     * @author Thomas Winkler <thomas.winkler@hof-university.de>
     * @function factory
     * @param {string} "TaskResourceHelper" Identifier
     * @param {string} '$resource'
     * @param {function} function($resource)
     * @returns {function} getTaskList
     * @returns {function} getMyTaskList
     * @returns {function} getOthresTaskList
     * @returns {function} getTaskDetail
     */
    .factory('TaskResourceHelper', ['$resource', function ($resource) {
        return {
            getTaskList: function (all) {
                if(all)
                    return $resource('task/listothers.json', {}, {
                        query: {method: 'GET', isArray: true}
                });
                else
                    return $resource('task/listmine.json', {}, {
                        query: {method: 'GET', isArray: true}
                    });
            },
            getMyTaskList: function () {
                return $resource('task/listmine.json', {}, {
                    query: {method: 'GET', isArray: true}
                });
            },
            getOthresTaskList: function () {
                return $resource('task/listothres.json', {}, {
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
    /**
     * @description This Method is used to indicate the Help feature in this version of OpenAgenda.
     *
     * @author Thomas Winkler <thomas.winkler@hof-university.de>
     * @function factory
     * @param {string} "Help" Identifier
     * @param {string} '$location'
     * @param {string} '$ModalDialog'
     * @param {function} function($location,ModalDialog)
     * @returns {function} show
     */
    .factory('Help', ['$location','ModalDialog', function ($location, ModalDialog) {
        return {
            show: function (){
                var path = $location.path();
                var url = '/template/modaldialog/generichelp.html';

                if("/dashboard" == path)
                    url = '/template/modaldialog/generichelp.html';
                if("/meeting" == path)
                    url = '/template/modaldialog/generichelp.html';

                var modalDefaults = {
                        templateUrl: url
                };
                ModalDialog.showModal(modalDefaults, {});
            }
        };
    }]);

