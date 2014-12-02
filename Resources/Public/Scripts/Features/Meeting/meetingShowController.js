/**
 * Created by Andi on 02.12.14.
 */

angular.module("Meeting")
    .controller('MeetingShowCtrl', ['$scope', '$routeParams', '$resource', "MeetingDetail",
    function($scope, $routeParams, $resource, MeetingDetail) {
        $scope.meetingId = $routeParams.meetingId;
        console.log($routeParams.meetingId);

        $scope.meeting = MeetingDetail($routeParams.meetingId).get(function (data) {
            console.log('success, got data: ', data);
        }, function (err) {
            alert('request failed');
        });

    }]);