/**
 * Created by Thomas on 02.12.14.
 */
/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Meeting")
    .controller('MeetingEditCtrl', ['$scope', '$http','$rootScope', '$routeParams', '$resource', "breadcrumbs", 'FileUploader', "MeetingResourceHelper", 'CommonHelperMethods',
        function ($scope, $http, $rootScope, $routeParams, $resource, breadcrumbs, FileUploader, MeetingResourceHelper, CommonHelperMethods) {
            $scope.breadcrumbs = breadcrumbs;
            console.log("Create meeting Conroller loaded");
            $scope.headerTitle = "Meeting anlegen";

            $scope.meetingId = $routeParams.meetingId;
            $scope.uploaders = [];


            if (typeof $scope.meetingId != "undefined") {
                $scope.headerTitle = "Meeting bearbeiten";
                $scope.meeting = MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    data.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(data.scheduledStartDate);
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

            function AgendaItem(sorting) {
                this.__identity = "38fa3590-9095-c080-da99-c15f1710cfed";
                this.title;
                this.description;
                this.creationDate;
                this.modificationDate;
                this.sorting = sorting;
                this.resources = [];

                $scope.uploaders.push(new FileUploader());
            }

            function Meeting() {
                this.__identity = "66d16457-2ebf-9a70-4368-dc73a0fd9edb";
                this.creationDate = new Date();
                this.endDate = null;
                this.modificationDate = new Date();
                this.scheduledStartDate = new Date();
                this.startDate = "'2015-01-05T12:00:00+01:00'";
                this.status = 0;
                this.title = null;
                this.place = null;
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

                $http.post('meeting/create.json', {newMeeting: $scope.sendThis}).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log("SUCCESS" + data);

                    }).
                    error(function(data, status, headers, config) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log("ERROR");

                    });
            };

            $scope.getUploader = function (idx) {
                return  $scope.uploaders[idx];
            }

            $scope.sendThis = {
                "scheduledStartDate": moment(new Date()).format('YYYY-MM-DD\THH:mm:ssP'),
                "title": "Test"
            };

            $scope.testJSON = {"devicetype":"test user","username":"newdeveloper"};

        }]);
