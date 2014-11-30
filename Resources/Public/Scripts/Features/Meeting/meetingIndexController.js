/**
 * Created by Thomas on 30.11.14.
 */

angular.module("Meeting", [])
    .controller('MeetingIndexCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs", "Meetinglist",
        function ($scope, $rootScope, $http, breadcrumbs, Meetinglist) {
            console.log("Dashboard Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;


            $scope.meetingList = Meetinglist.query(function (data) {
                console.log('success, got data: ', data);

            }, function (err) {
                alert('request failed');
            });

            //$rootScope.changeToolBar("");


        }])
    .controller('DatepickerCtrl', function ($scope) {



        $scope.toggleMin = function() {
            $scope.minDate = $scope.minDate ? null : new Date();
        };
        $scope.toggleMin();

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };
        $scope.open2 = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope.opened2 = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };


        $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[1];
    });