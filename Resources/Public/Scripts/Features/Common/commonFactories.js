/**
 * Created by Thomas on 27.11.14.
 */
angular.module("CommonFactories", [])
    .factory('Meetinglist', ['$resource',
        function($resource){
            return $resource('meeting/list.json', {}, {
                query: {method:'GET', isArray:true}
            });
        }])
    .factory('MeetingDetail', ['$resource',
        function($resource){
            return function(id) {
                return $resource('meeting/:meetingId/show.json',{meetingId:id},{
                    get: {method:'GET'}
                });
            };
        }]);

