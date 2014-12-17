/**
 * Created by Thomas on 02.12.14.
 */
/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Meeting")
    .controller('MeetingEditCtrl', ['$scope', '$http','$rootScope', '$routeParams', '$resource', "breadcrumbs", 'FileUploader', "MeetingResourceHelper", 'CommonHelperMethods', 'OpenAgenda.Data.Utility',
        function ($scope, $http, $rootScope, $routeParams, $resource, breadcrumbs, FileUploader, MeetingResourceHelper, CommonHelperMethods, oaUtility) {
            $scope.breadcrumbs = breadcrumbs;
            console.log("Create meeting Conroller loaded");
            $scope.headerTitle = "Meeting anlegen";

            $scope.meetingId = $routeParams.meetingId;
            $scope.uploaders = [];

            if (typeof $scope.meetingId != "undefined") {
                $scope.headerTitle = "Meeting bearbeiten";
                $scope.meeting = MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    console.log('success, got data: ', data);

                    for(var i = 0; i<= $scope.meeting.agendaItems.length; i++)
                    {
                        if (typeof $scope.uploaders[i] === "undefined") {
                            $scope.uploaders.push(new FileUploader());
                        }
                    }
                }, function (err) {
                    alert('request failed');
                });
            }
            if ((typeof $scope.meetingId != "undefined") && !$scope.editMode ){
                $scope.headerTitle = "Meeting anzeigen";
            }

            function AgendaItem(count) {
                this.title = 'TOP #' + count
                this.description = 'Description';
                this.resources = [];

                $scope.uploaders.push(new FileUploader());
            }

            function Meeting() {
                this.endDate = null;
                this.startDate = null;
                this.scheduledStartDate = new Date();
                this.status = 0;
                this.title = 'Meeting';
                this.location = 'Location';
                this.agendaItems = [new AgendaItem(1)];
                this.invitations = [];
            }

            function Invitation(mail) {
                this.id = "USERID";
                this.mail = mail;
            }

            if (typeof $scope.meeting === "undefined") {
                $scope.meeting = new Meeting();
                console.log("Meeting ist undefinded");
            }

            $scope.addNewAgendaItem = function () {
                $scope.meeting.agendaItems.push(new AgendaItem($scope.meeting.agendaItems.length + 1));
            };

            $scope.addNewInvitation = function (mail) {
                $scope.meeting.invitations.push(new Invitation(mail))

            };
            $scope.deleteInvitation = function (idx) {
                $scope.meeting.invitations.splice(idx, 1);
            };

            $scope.$watchCollection('meeting', function (newValue, oldValue) {
                console.log(newValue);
            });

            $scope.sendMeetingData = function () {
                console.log("SENDEN");
                console.log($scope.meeting);

                $http.post('meeting/create.json', { newMeeting: oaUtility.jsonCast($scope.meeting) }, { proxy: true }).
                    success(function(data, status, headers, config) {
                        console.log(data);
                        console.log('New identity: ' + data.__identity);
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log("SUCCESS");

                    }).
                    error(function(data, status, headers, config) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log(data);
                        console.log("ERROR");

                    });
            };

            $scope.getUploader = function (idx) {
                return  $scope.uploaders[idx];
            };

        }]);
