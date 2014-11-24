var ApplicationControllers = angular.module('ApplicationControllers', []);

ApplicationControllers.controller('DashboardCtrl', ['$scope', '$http',
    function ($scope, $http) {
        console.log("kogretlörelkg");
        $http.get('/openagenda.application/dashboard/index.json').success(function(data) {
            $scope.data = data;
        });
        $scope.orderProp = 'userPriority';
    }]);

ApplicationControllers.controller('MeetingCtrl', ['$scope', '$http',
    function ($scope, $http) {
        //$http.get('/openagenda.application/meetings/index.json').success(function(data) {
            $scope.meetings = [{"meetingId":"1", "meetingName":"Meeting 1" }];  //data;
        //});
        $scope.orderProp = 'startDateTime';
    }]);
ApplicationControllers.controller('MeetingDetailCtrl', ['$scope', '$routeParams',
    function($scope, $routeParams) {
        $scope.meeting = $routeParams.meetingId;
    }]);

ApplicationControllers.controller('TaskCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.get('/openagenda.application/task/index.json').success(function(data) {
            $scope.meetings = data;
        });
        $scope.orderProp = 'dueDate';
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
        $http.get('/openagenda.application/setting/index.json').success(function(data) {
            $scope.setting = data;
        });
    }]);