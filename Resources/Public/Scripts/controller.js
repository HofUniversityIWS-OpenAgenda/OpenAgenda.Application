var ApplicationControllers = angular.module('ApplicationControllers', []);

ApplicationControllers.controller('TaskCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.get('/openagenda.application/task/index.json').success(function(data) {
            $scope.meetings = data;
        });
        $scope.orderProp = 'dueDate';
    }]);



ApplicationControllers.controller('MeetingExecuteCtrl', ['$scope', '$rootScope', '$routeParams', '$resource', "breadcrumbs", "MeetingDetail",
    function ($scope, $rootScope, $routeParams, $resource, breadcrumbs, MeetingDetail, Meetinglist) {
        $scope.meetingId = $routeParams.meetingId;
        console.log($routeParams.meetingId);

        $scope.getDateFromJSONString = function (string) {
            return new Date(string.substr(1, string.length - 2));
        };

        $scope.meeting = MeetingDetail($routeParams.meetingId).get(function (data) {
            console.log('success, got data: ', data);
            console.log('datum', data.startDate);
            data.startDate = $scope.getDateFromJSONString(data.startDate);
            data.formatStartDate = DateFormatter.format(new Date(data.startDate), "Y/m/d"); // H:i") + ' Uhr';
            data.formatTime = DateFormatter.format(new Date(data.startDate), "H:i") + ' Uhr';

            data.scheduledStartDate = $scope.getDateFromJSONString(data.scheduledStartDate);
            data.formatScheduledStartDate = DateFormatter.format(data.scheduledStartDate, "Y/m/d");
            data.formatScheduledTime = DateFormatter.format(new Date(data.startDate), "H:i") + ' Uhr';



            console.log('datum neu', data.formatStartDate);
        }, function (err) {
            alert('request failed');
        });

        $scope.getProtocolItem = function(index){
            console.log('getProtocolItem Start');
            for (var i = 0; $scope.meeting.protocolItems.length; i++)
            {
                if ($scope.meeting.protocolItems[i].sorting = index)
                {
                    return $scope.meeting.protocolItems[i];
                }
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

