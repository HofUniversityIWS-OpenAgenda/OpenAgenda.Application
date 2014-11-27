/**
 * Created by Thomas on 27.11.14.
 */
angular.module("CommonFactories", [])
    .factory('Meetinglist', ['$resource',
        function($resource){
            return $resource('meeting/list.json', {}, {
                query: {method:'GET', isArray:true}
            });
        }]);