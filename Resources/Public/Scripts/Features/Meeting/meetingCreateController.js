/**
 * Created by Thomas on 02.12.14.
 */
/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Meeting")
    .controller('MeetingCreateCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs",
    function ($scope, $rootScope, $http, breadcrumbs, Meetinglist) {
        $scope.breadcrumbs = breadcrumbs;
        console.log("Create meeting Conroller loaded");
        }]);
