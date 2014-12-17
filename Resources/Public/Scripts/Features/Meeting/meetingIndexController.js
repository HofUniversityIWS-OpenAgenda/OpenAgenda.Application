/**
 * This Module contains the Meeting Index
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("Meeting", [])
    /*Filter to show only the upcomming meetings in the table*/
    .filter('upComing', function () {
        return function (items, field, startDate, endDate) {

            var timeStart = startDate;
            var timeEnd = endDate; // 1 day in ms
            return items.filter(function (item) {
                return (item[field] > timeStart && item[field] < timeEnd);
            });
        };
    })
    .controller('MeetingIndexCtrl', ['$scope', '$rootScope', '$filter','$resource', "breadcrumbs", "MeetingResourceHelper","CommonHelperMethods",
        function ($scope, $rootScope, $filter, $resource, breadcrumbs, MeetingResourceHelper, CommonHelperMethods) {
            console.log("Meeting Index Controller Loaded");
            $scope.breadcrumbs = breadcrumbs;
            $scope.loading = true;
            if(!$rootScope.mic)
                $rootScope.mic = new Object();

            $scope.meetingList = MeetingResourceHelper.getMeetingList().query(function (data) {
                console.log('success, got meeting: ', $scope.meetingList);
                $scope.loading = false;
            }, function (err) {
                alert('request failed');
            });

            //$rootScope.changeToolBar("");

            /* Below are the requirements for Datepicker
             *
             * Due to a bug in ui-bootstrap library, the first date is not formatted properly
             */
            $scope.format = 'dd.MM.yyyy';

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
                startingDay: 1
            };

            /*Using rootScope to save current Selection globally*/

            $scope.getStartDate = function () {

                if ($rootScope.mic.selectedStartDate) {
                    return $rootScope.mic.selectedStartDate;
                }
                else
                {
                    console.log("START IS LEER");
                    $rootScope.mic.selectedStartDate = new Date();
                    return $rootScope.mic.selectedStartDate;
                }
            };
            $scope.getEndDate = function () {
                var then = new Date();
                then.setDate(then.getDate() + 14);

                if ($rootScope.mic.selectedEndDate)
                    return $rootScope.mic.selectedEndDate;
                else
                {
                    $rootScope.mic.selectedEndDate = then;
                    return $rootScope.mic.selectedEndDate;
                }
            };

            $scope.getSearchText = function () {
                if ($rootScope.mic.searchText)
                    return $rootScope.mic.searchText;
                else
                {
                    $rootScope.mic.searchText ="";
                    return $rootScope.mic.searchText;
                }
            }

            $scope.resetMeetingFilter = function () {
                $rootScope.mic.selectedEndDate = null;
                $rootScope.mic.selectedStartDate = null;
                $rootScope.mic.searchText ="";

                $scope.startDate = $scope.getStartDate();
                $scope.endDate = $scope.getEndDate();
                $scope.searchText = $scope.getSearchText();
            }

            /*Set start and enddate to filtering meetings*/
            $scope.startDate = $scope.getStartDate();
            $scope.endDate = $scope.getEndDate();
            $scope.searchText = $scope.getSearchText();

            $scope.$watch("startDate", function () {
                $rootScope.mic.selectedStartDate = $scope.startDate;
            });

            $scope.$watch("endDate", function () {
                $rootScope.mic.selectedEndDate = $scope.endDate;
            });

            $scope.$watch("searchText", function () {
                $rootScope.mic.searchText = $scope.searchText;
            });

        }]);
