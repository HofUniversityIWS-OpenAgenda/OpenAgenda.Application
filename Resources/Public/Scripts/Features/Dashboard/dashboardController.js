/**
 * This Module defines the Dashboard
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */
angular.module("Dashboard", [])
    .controller('DashboardCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs", "MeetingResourceHelper",'TaskResourceHelper', 'CommonHelperMethods',
        function ($scope, $rootScope, $http, breadcrumbs, MeetingResourceHelper, TaskResourceHelper, CommonHelperMethods) {
            console.log("Dashboard Controller Loaded");

            //Init
            $scope.breadcrumbs = breadcrumbs;
            $scope.upcomingMeetings = [];
            $scope.currentUser = "Thomas"; // TODO: From where?
            $scope.events = [];

            $scope.meetingList = MeetingResourceHelper.getMeetingList().query(function () {
                angular.forEach( $scope.meetingList, function (meeting) {
                    meeting.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(meeting.scheduledStartDate);
                    $scope.events.push( {title: meeting.title, start: new Date(meeting.scheduledStartDate) });
                });
                $scope.findUpcomingMeetings($scope.meetingList);

                // angular.copy( $scope.meetingList, $rootScope.meetingListGlobal);
            }, function (err) {
                alert('request failed');
            });

            //Get all Tasks TODO: Only my Tasks
            $scope.needToBeDoneTasks  = TaskResourceHelper.getTaskList().query(function (data) {
                console.log('success, got task: ', data);
                angular.forEach( $scope.needToBeDoneTasks, function (task) {
                    task.dueDate = CommonHelperMethods.getDateFromJSONString(task.dueDate);
                });
            }, function (err) {
                alert('request failed');
            });

            $scope.findUpcomingMeetings = function (meetingList) {
                //search for upcoming Meetings
                var now = new Date();
                angular.forEach( meetingList, function (meeting) {
                    if (now <= meeting.scheduledStartDate)
                    {
                        $scope.upcomingMeetings.push(meeting);
                    }
                });
            };

            $scope.getNotifications = function () {
                return $rootScope.notifications;
            };
            //$rootScope.changeToolBar("<div>IF NEEDED TOOLBAR</div>");

        }])
        /*  Controller especially for the Calendar in the Dashboard
        *   Due to a bug in the Library only meetings of the current view are shown in the Calendar
        * */

    .controller('DashboardCalendarCtrl', ['$scope', '$compile', "uiCalendarConfig",
        function ($scope, $compile, uiCalendarConfig) {
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            /* Alert on eventClick */
            $scope.alertOnEventClick = function (date, jsEvent, view) {
                $scope.alertMessage = (date.title + ' ausgewählt');
            };
            /* Alert on Drop */
            $scope.alertOnDrop = function (event, delta, revertFunc, jsEvent, ui, view) {
                $scope.alertMessage = ('Event Droped to make dayDelta ' + delta);
            };
            /* Alert on Resize */
            $scope.alertOnResize = function (event, delta, revertFunc, jsEvent, ui, view) {
                $scope.alertMessage = ('Event Resized to make dayDelta ' + delta);
            };
            /* Add and removes an event source of choice */
            $scope.addRemoveEventSource = function (sources, source) {
                var canAdd = 0;
                angular.forEach(sources, function (value, key) {
                    if (sources[key] === source) {
                        sources.splice(key, 1);
                        canAdd = 1;
                    }
                });
                if (canAdd === 0) {
                    sources.push(source);
                }
            };

            /* Change View */
            $scope.changeView = function (view, calendar) {
                uiCalendarConfig.calendars[calendar].fullCalendar('changeView', view);
            };
            /* Change View */
            $scope.renderCalender = function (calendar) {
                if (uiCalendarConfig.calendars[calendar]) {
                    uiCalendarConfig.calendars[calendar].fullCalendar('render');
                }
            };
            /* Render Tooltip */
            $scope.eventRender = function (event, element, view) {
                element.attr({
                    'tooltip': event.title,
                    'tooltip-append-to-body': true
                });
                $compile(element)($scope);
            };
            $scope.locale = "de";
            /* Config object */
            $scope.uiConfig = {
                calendar: {
                    height: 450,
                    editable: false,
                    firstDay: 1,
                    header: {
                        left: 'title',
                        center: '',
                        right: ''
                    },
                    eventClick: $scope.alertOnEventClick,
                    eventDrop: $scope.alertOnDrop,
                    eventResize: $scope.alertOnResize,
                    eventRender: $scope.eventRender
                }
            };

            $scope.changeLang = function () {
                $scope.uiConfig.calendar.dayNames = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
                $scope.uiConfig.calendar.dayNamesShort = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];

            };
            $scope.changeTo = "German";
            $scope.changeLang();

            $scope.eventSources = [$scope.events];
        }]);
