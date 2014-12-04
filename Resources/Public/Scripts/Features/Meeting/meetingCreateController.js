/**
 * Created by Thomas on 02.12.14.
 */
/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Meeting")
    .controller('MeetingCreateCtrl', ['$scope', '$rootScope', '$resource', "breadcrumbs",
    function ($scope, $rootScope, $http, breadcrumbs, Meetinglist) {
        $scope.breadcrumbs = breadcrumbs;
        console.log("Create meeting Conroller loaded");


        function agendaItem(sorting) {
            this.__identity = "38fa3590-9095-c080-da99-c15f1710cfed";
            this.title;
            this.description;
            this.creationDate;
            this.modificationDate;
            this.sorting = sorting;
            this.resources = [];
        };

        function meeting() {
            this.__identity = "66d16457-2ebf-9a70-4368-dc73a0fd9edb";
            this.creationDate = new Date();
            this.endDate = null;
            this.modificationDate = new Date();
            this.scheduledStartDate = new Date();
            this.startDate = "'2015-01-05T12:00:00+01:00'";
            this.status = 0;
            this.title = null;
            this.place = null;
            this.agendaItems = [new agendaItem(1)];
            this.invitations = [];
        };

        function invitation(mail) {
            this.id = "USERID";
            this.mail = mail;
        };


        $scope.meeting = new meeting();

        $scope.addNewAgendaItem = function () {
            $scope.meeting.agendaItems.push(new agendaItem($scope.meeting.agendaItems.length + 1));
        };

        $scope.addNewInvitation = function (mail) {
            $scope.meeting.invitations.push(new invitation(mail))

        };
        $scope.deleteInvitation = function ( idx ) {
            $scope.meeting.invitations.splice(idx, 1);
        };

        $scope.$watchCollection('meeting', function(newValue, oldValue) {
            console.log(newValue);
        });

        $scope.sendMeetingData = function () {
            console.log("SEND DATA " + $scope.uploader);
        };
    }])
    .controller('uploadCtrl', function($scope, $rootScope, FileUploader) {
        console.log("Upload Controller loaded");

        $rootScope.uploader = new FileUploader();
        console.log($rootScope.uploader);

        $scope.getUploader = function() {
            return  $rootScope.uploader;
        };

    })
    .directive("pointMe", function() {
        return {
            restrict : "AC",
            link : function(scope, element, attrs) {

                element.css("cursor", "pointer");

            }
        };
    });
