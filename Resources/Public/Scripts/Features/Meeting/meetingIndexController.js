/**
 * Created by Thomas on 30.11.14.
 */

angular.module("Meeting", [])
    .filter('upComing', function () {
        return function (items, field, startDate, endDate) {

            var timeStart = startDate;
            var timeEnd = endDate; // 1 day in ms
            return items.filter(function (item) {
                return (item[field] > timeStart && item[field] < timeEnd);
            });
        };
    })
    .controller('MeetingIndexCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs", "Meetinglist",
        function ($scope, $rootScope, $http, breadcrumbs, Meetinglist) {
            console.log("Dashboard Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

            $scope.getDateFromJSONString = function (string) {
                return new Date(string.substr(1, string.length - 2));
            };

            $scope.meetingList = Meetinglist.query(function (data) {
                angular.forEach($scope.meetingList, function (meeting) {
                    meeting.startDate = $scope.getDateFromJSONString(meeting.startDate);
                });
                console.log('success, got data: ', $scope.meetingList);

            }, function (err) {
                alert('request failed');
            });

            //$rootScope.changeToolBar("");

            /* Datepicker*/
            $scope.toggleMin = function () {
                $scope.minDate = $scope.minDate ? null : new Date();
            };
            $scope.toggleMin();

            $scope.openStart = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.openedStart = true;
            };

            $scope.openEnd = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();

                $scope.openedEnd = true;
            };

            $scope.dateOptions = {
                formatYear: 'yyyy',
                startingDay: 1,
                "init-date": new Date()
            };

            var tempDate = new Date().getFullYear() + "/" + new Date().getMonth() + "/" + new Date().getDay();
            $scope.endDate = tempDate;
            $scope.startDate = tempDate;

            $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
            $scope.format = $scope.formats[1];
        }]);
