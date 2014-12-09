var ApplicationControllers = angular.module('ApplicationControllers', []);

ApplicationControllers.controller('TaskCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.get('/openagenda.application/task/index.json').success(function(data) {
            $scope.meetings = data;
        });
        $scope.orderProp = 'dueDate';
    }]);



ApplicationControllers.controller('MeetingExecuteCtrl', ['$scope', '$rootScope', '$filter','$routeParams', '$resource', "breadcrumbs", "MeetingResourceHelper",
    function ($scope, $rootScope, $filter, $routeParams, $resource, breadcrumbs, MeetingResourceHelper) {
        $scope.meetingId = $routeParams.meetingId;
        console.log($routeParams.meetingId);
        $scope.breadcrumbs = breadcrumbs;

        $scope.getDateFromJSONString = function (string) {
            return new Date(string.substr(1, string.length - 2));
        };

        $scope.meeting = MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
            console.log('success, got data: ', data);
            console.log('datum', data.startDate);
            if (data.startDate)
            {

                data.startDate = $scope.getDateFromJSONString(data.startDate);
                data.formatStartDate = DateFormatter.format(new Date(data.startDate), "Y/m/d"); // H:i") + ' Uhr';
                data.formatStartTime = DateFormatter.format(new Date(data.startDate), "H:i") + ' Uhr';
            }

            data.scheduledStartDate = $scope.getDateFromJSONString(data.scheduledStartDate);
            data.formatScheduledStartDate = DateFormatter.format(data.scheduledStartDate, "Y/m/d");
            data.formatScheduledTime = DateFormatter.format(new Date(data.startDate), "H:i") + ' Uhr';


        }, function (err) {
            alert('request failed');
        });

        $scope.task;

        $scope.getProtocolItem = function(sorting){
            var found = false;
            for (var i = 0; $scope.meeting.protocolItems.length; i++)
            {
                if ($scope.meeting.protocolItems[i].sorting == sorting)
                {
                    found = true;
                    return $scope.meeting.protocolItems[i];
                }
            }
            if(!found)
            {
                //add new Item
                var newProtocolItem = function ProtocollItem(sorting) {
                    this.__identity;
                    this.description=null;
                    this.creationDate = new Date();
                    this.modificationDate;
                    this.sorting = sorting;
                }
                $scope.meeting.protocolItems.push(newProtocolItem);
                return newProtocolItem;
            }
        };

        $scope.imgTask = {

        };

        $scope.invitedUsers = [
            {value: 1, text: 'tt@tt.de'},
            {value: 2, text: 'xx@tt.de'},
            {value: 3, text: 'txxt@tt.de'},
            {value: 4, text: 'tfggt@tt.de'}
        ];

        $scope.showStatus = function() {
            var selected = $filter('filter')($scope.invitedUsers, {value: $scope.imgTask.user});
            return ($scope.imgTask.user && selected.length) ? selected[0].text : 'Verantwortlichen wählen';
        };

        $scope.startMeetng = function(){
            if ($scope.meeting.startDate == null)
            {
                $scope.meeting.startDate = new Date();

            }
        };
        $scope.endMeetng = function(){
            if ($scope.meeting.endDate == null)
            {
                $scope.meeting.endDate = new Date();

            }
        };

    }]);

ApplicationControllers.controller('CalendarCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.get('/openagenda.application/Calendar/index.json').success(function(data) {
            $scope.events = data;
        });
        $scope.orderProp = 'startDateTime';
    }]);
ApplicationControllers.controller('CalendarDetailCtrl', ['$scope', '$routeParams',
    function($scope, $routeParams) {
        $scope.event = $routeParams.eventId;
    }]);

ApplicationControllers.controller('SettingCtrl', ['$scope', '$http',
    function ($scope, $http) {
        console.log("Settings Controller Loaded");
        $http.get('/openagenda.application/setting/index.json').success(function(data) {
            $scope.setting = data;
        });
    }]);

