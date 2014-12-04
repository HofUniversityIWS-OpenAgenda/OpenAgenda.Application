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
            console.log("Meeting Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;

            $scope.getDateFromJSONString = function (string) {
                return new Date(string.substr(1, string.length - 2));
            };

            $scope.meetingList = Meetinglist.query(function (data) {
                angular.forEach($scope.meetingList, function (meeting) {
                    meeting.scheduledStartDate = $scope.getDateFromJSONString(meeting.scheduledStartDate);
                    meeting.formatStartDate = DateFormatter.format(meeting.scheduledStartDate, "Y/m/d H:i") + ' Uhr';

                    switch (meeting.status) {
                        case 0:
                            meeting.formatStatus = "vorgeplant";
                            break;
                        case 1:
                            meeting.formatStatus = "geplant";
                            break;
                        case 2:
                            meeting.formatStatus = "läuft";
                            break;
                        case 3:
                            meeting.formatStatus = "abgeschlossen";
                            break;

                    }
                    ;
                });
                console.log('success, got data: ', $scope.meetingList);

            }, function (err) {
                alert('request failed');
            });

            //$rootScope.changeToolBar("");

            $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/d', 'dd.MM.yyyy', 'shortDate'];
            $scope.format = $scope.formats[1];
            console.log($scope.format)

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
            var now = new Date();
            var tempDate = now.getFullYear() + "/" + (now.getMonth()+1) + "/" + now.getDay();
            $scope.startDate = tempDate;

            var then = new Date();
            then.setDate(then.getDate());

            var tempDate2 = then.getFullYear() + "/" + (then.getMonth()+1) + "/" + (then.getDay());
            $scope.endDate = tempDate2;

        }]);
