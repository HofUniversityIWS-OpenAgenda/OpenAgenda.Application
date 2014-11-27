/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Dashboard", [])
.controller('DashboardCtrl', ['$scope','$rootScope', '$resource', "breadcrumbs", "Meetinglist", "$sce",
        function ($scope, $rootScope, $http, breadcrumbs, Meetinglist, $sce) {
        console.log("Dashboard Controller Loaded");
        $scope.breadcrumbs = breadcrumbs;
        /*$http.get('/openagenda.application/dashboard/index.json').success(function(data) {
            $scope.data = data;
        });*/
        $scope.upcomingMeetings = [];
        $scope.needToBeDoneTasks = [1,2,3];

        $scope.currentUser = "Thomas"; // From where?

        $scope.meetingList = Meetinglist.query(function(data){
            console.log('success, got data: ', data);
            $scope.findUpcomingMeetings(data);

        }, function(err){
            alert('request failed');
        });

        $scope.findUpcomingMeetings = function(meetingList){
            //search for upcoming Meetings
            $scope.upcomingMeetings = meetingList;
        };

        $scope.getNotifications = function() {
            return $rootScope.notifications;
        };
        $scope.t ="<div style='border: solid'>d</div>";

        $scope.to_trusted = function() {
            return $sce.trustAsHtml($scope.t);
        };
    }]);