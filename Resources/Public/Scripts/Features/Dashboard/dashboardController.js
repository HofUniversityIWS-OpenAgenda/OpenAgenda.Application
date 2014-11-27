/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Dashboard", [])
.controller('DashboardCtrl', ['$scope', '$http', "breadcrumbs", "Meetinglist",
    function ($scope, $http, breadcrumbs, Meetinglist) {
        console.log("Dashboard Controller Loaded");
        $scope.breadcrumbs = breadcrumbs;
        /*$http.get('/openagenda.application/dashboard/index.json').success(function(data) {
            $scope.data = data;
        });*/
        $scope.upcomingMeetings = [];

        $scope.currentUser = "Thomas"; // woher?
        $scope.test = "Tests";
        $scope.meetingList = Meetinglist.query(function(data){
            console.log('success, got data: ', data);
            $scope.getUpcomingMeetings(data);
        }, function(err){
            alert('request failed');
        });

        $scope.getUpcomingMeetings = function(meetingList){
            //neuere Meetings raussuchen
            $scope.upcomingMeetings = meetingList;
        }
    }]);