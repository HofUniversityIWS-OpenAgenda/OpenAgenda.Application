/**
 * @memberOf angular_module
 * @description TThis Module contains the Meeting Index
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

angular.module("Meeting", [])
/**
 * @function upComing
 * @memberOf angular_module.Meeting
 * @description Filter to show only the upcomming meetings in the table
 */
    .filter('upComing', function () {
        return function (items, field, startDate, endDate) {
            var timeStart = startDate;
            var timeEnd = endDate; // 1 day in ms
            return items.filter(function (item) {
                return (item[field] > timeStart && item[field] < timeEnd);
            });
        };
    })
/**
 * @class angular_module.Meeting.MeetingIndexCtrl
 */
    .controller('MeetingIndexCtrl', ['$scope', '$rootScope', '$location', '$filter', '$http', '$resource', "breadcrumbs", "MeetingResourceHelper", "CommonHelperMethods", "ModalDialog",
        function ($scope, $rootScope, $location, $filter, $http, $resource, breadcrumbs, MeetingResourceHelper, CommonHelperMethods, ModalDialog) {
            console.log("Meeting Index Controller loaded");
            /**@memberOf angular_module.Meeting.MeetingIndexCtrl */

            $scope.breadcrumbs = breadcrumbs;

            /**@memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Show loading indecator*/
            $scope.loading = true;
            if (!$rootScope.mic)
                $rootScope.mic = {};

            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Reload all Meetings
             */
            function reloadMeetings() {
                $scope.meetingList = MeetingResourceHelper.getMeetingList().query(function (data) {
                    angular.forEach($scope.meetingList, function (meeting) {
                        meeting.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(meeting.scheduledStartDate);
                    });
                    $scope.loading = false;
                }, function (err) {
                    alert('request failed');
                });
            };
            reloadMeetings();

            //$rootScope.changeToolBar("");

            /*
             *Below are the requirements for Datepicker
             * Due to a bug in ui-bootstrap library, the first shown date is not formatted properly
             */
            $scope.format = 'dd.MM.yyyy';
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             */
            $scope.toggleMin = function () {
                $scope.minDate = $scope.minDate ? null : new Date();
            };

            $scope.toggleMin();
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @param {object} event For UI Picker
             */
            $scope.openStart = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.openedStart = true;
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @param {object} event For UI Picker
             */
            $scope.openEnd = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();

                $scope.openedEnd = true;
            };
            /**
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Settings for Datepicker
             */
            $scope.dateOptions = {
                formatYear: 'yyyy',
                startingDay: 1
            };

            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Using rootScope to get current Selection globally. Get the global variable selectedStartDate
             */
            $scope.getStartDate = function () {

                if ($rootScope.mic.selectedStartDate) {
                    return $rootScope.mic.selectedStartDate;
                }
                else {
                    $rootScope.mic.selectedStartDate = new Date();
                    return $rootScope.mic.selectedStartDate;
                }
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Using rootScope to get current Selection globally. Get the global variable selectedEndDate
             */
            $scope.getEndDate = function () {
                var then = new Date();
                then.setDate(then.getDate() + 14);

                if ($rootScope.mic.selectedEndDate)
                    return $rootScope.mic.selectedEndDate;
                else {
                    $rootScope.mic.selectedEndDate = then;
                    return $rootScope.mic.selectedEndDate;
                }
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Using rootScope to get current Selection globally. Get the global variable searchText
             */
            $scope.getSearchText = function () {
                if ($rootScope.mic.searchText)
                    return $rootScope.mic.searchText;
                else {
                    $rootScope.mic.searchText = "";
                    return $rootScope.mic.searchText;
                }
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Reset all of the globally stored filter information
             */
            $scope.resetMeetingFilter = function () {
                $rootScope.mic.selectedEndDate = null;
                $rootScope.mic.selectedStartDate = null;
                $rootScope.mic.searchText = "";

                $scope.startDate = $scope.getStartDate();
                $scope.endDate = $scope.getEndDate();
                $scope.searchText = $scope.getSearchText();
            };

            /*Set start and end date to filtering meetings*/
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
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Delete a certain Meeting
             * @param {int} id ID of the Meeting
             */
            $scope.deleteMeeting = function (id) {
                $http.get('/meeting/' + id + '/delete.json').
                    success(function (data, status, headers, config) {
                        var modalOptions = {
                            headerText: 'Erfolg',
                            bodyText: 'Das Meeting wurde erfolgreich gelöscht!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/success.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                        reloadMeetings();
                    }).
                    error(function (data, status, headers, config) {

                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Beim Löschen des Meetings ist ein Fehler aufgetreten! Versuchen Sie es später erneut!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    });
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Cancel a certain Meeting
             * @param {int} id ID of the Meeting
             */
            $scope.cancelMeeting = function (id) {
                $http.post("/meeting/cancel.json", {"meeting": id}).
                    success(function (data, status, headers, config) {
                        var modalOptions = {
                            headerText: 'Erfolg',
                            bodyText: 'Das Meeting wurde erfolgreich abgesagt!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/success.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                        reloadMeetings();
                    }).
                    error(function (data, status, headers, config) {

                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Beim Ändern des Meeting-Status ist ein Fehler aufgetreten! Versuchen Sie es später erneut!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);

                    });
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingIndexCtrl
             * @description Trigger event on backend, that will send an Invitations to all invitees.
             * @param {int} id ID of the Meeting
             */
            $scope.sendInvitations = function (id) {
                $http.get("/meeting/" + id + "/commit.json").
                    success(function (data, status, headers, config) {
                        var modalOptions = {
                            headerText: 'Erfolg',
                            bodyText: 'Die Einladungen wurden erfolgreich verschickt!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/success.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                        reloadMeetings();
                    }).
                    error(function (data, status, headers, config) {

                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Beim Verschicken der Einladungen ist ein Fehler aufgetreten! Versuchen Sie es später erneut!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);

                    });
            };
        }]);
