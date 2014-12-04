/**
 * Created by Thomas on 27.11.14.
 */
angular.module("CommonFactories", [])
    .factory('CommonHelperMethods', function() {
        return {
            getDateFromJSONString: function (string) {
                return new Date(string.substr(1, string.length - 2));
            }
        };
    })
    .factory('MeetingResourceHelper', ['$resource', function($resource) {
        return {
            getMeetingList: function() {
                return $resource('meeting/list.json', {}, {
                    query: {method:'GET', isArray:true}
                });
            },
            getMeetingDetail: function(id) {
                return $resource('meeting/:meetingId/show.json',{meetingId:id}, {
                    get: {method:'GET'}
                });
            },
            getMeetingInvitations: function(id) {
                return $resource('meeting/:meetingId/showstatusofinvitations.json',{meetingId:id}, {
                    get: {method:'GET'}
                });
            }
        };

    }])
    .factory('TaskResourceHelper', ['$resource', function($resource) {
        return {
            getTaskList: function() {
                return $resource('task/list.json', {}, {
                    query: {method:'GET', isArray:true}
                });
            },
            getTaskDetail: function(id) {
                return $resource('meeting/:taskId/show.json',{taskId:id}, {
                    get: {method:'GET'}
                });
            }
        };

    }]);

