/**
 * Created by Thomas on 02.12.14.
 */
/**
 * Created by Thomas on 27.11.14.
 */
angular.module("Meeting")
    .controller('MeetingEditCtrl', ['$scope','$filter', '$http', '$rootScope', '$location', '$routeParams', '$resource', "breadcrumbs", 'FileUploader', "MeetingResourceHelper", 'CommonHelperMethods', 'OpenAgenda.Data.Utility', 'ModalDialog',
        function ($scope, $filter, $http, $rootScope, $location, $routeParams, $resource, breadcrumbs, FileUploader, MeetingResourceHelper, CommonHelperMethods, oaUtility, ModalDialog) {
            $scope.breadcrumbs = breadcrumbs;
            $scope.loading = true;
            console.log("Meeting Edit Controller loaded");

            $scope.meetingsRoles = [{ "value": "OpenAgenda.Application:Listener", "text": "Zuhörer" },
                                    { "value": "OpenAgenda.Application:Participant", "text": "Teilnehmer" },
                                    { "value": "OpenAgenda.Application:MinuteTaker", "text": "Protokol-Führer" },
                                    { "value": "OpenAgenda.Application:MeetingChair", "text": "Meeting-Leiter" },
                                    { "value": "OpenAgenda.Application:MeetingManager", "text": "Meeting-Manager" },
                                    { "value": "OpenAgenda.Application:Chairman", "text": "Vorsitzender" },
                                    { "value": "OpenAgenda.Application:Administrator", "text": "Administrator" }
                                    ];

            $scope.meetingId = $routeParams.meetingId;
            $scope.uploaders = [];

            $scope.remoteUsers = [];
            $http.get('person/index.json').success(function(persons) { $scope.remoteUsers = persons; });

            if (typeof $scope.meetingId === "undefined")
                $scope.editMode = true;

            if (typeof $scope.meetingId != "undefined") {
                $scope.meeting = MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    data.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(data.scheduledStartDate);
                    for (var i = 0; i <= $scope.meeting.agendaItems.length; i++) {
                        if (typeof $scope.uploaders[i] === "undefined") {
                            $scope.uploaders.push(new FileUploader());
                        }
                    }
                    console.log("Got Meeting: ", $scope.meeting)
                    $scope.loading = false;
                }, function (err) {
                    alert('request failed');
                });
            }


            function AgendaItem(count) {
                this.title = null;
                this.description = null;
                this.resources = [];
                this.sorting = count;
                $scope.uploaders.push(new FileUploader());
            }

            function Meeting() {
                this.endDate = null;
                this.startDate = null;
                this.scheduledStartDate = new Date();
                this.status = 0;
                this.title = null;
                this.location = null;
                this.agendaItems = [new AgendaItem(1)];
                this.invitations = [];
            }

            function Invitation(personIdentity) {
                this.participant = personIdentity;
            }

            if (typeof $scope.meeting === "undefined") {
                $scope.meeting = new Meeting();
            }

            $scope.addNewAgendaItem = function () {
                $scope.meeting.agendaItems.push(new AgendaItem($scope.meeting.agendaItems.length + 1));
            };

            $scope.addNewInvitation = function (mail) {
                var single_User = $filter('filter')($scope.remoteUsers, function (person) {return person.$mail === mail; })[0];
                $scope.meeting.invitations.push(new Invitation(single_User.__identity))

            };
            $scope.deleteInvitation = function (idx) {
                $scope.meeting.invitations.splice(idx, 1);
            };

            $scope.$watchCollection('meeting', function (newValue, oldValue) {
                //console.log(newValue);
            });

            $scope.sendMeetingData = function () {
                if ($scope.checkEntries()) {

                    if(typeof $scope.meetingId != "undefined")
                        var sendUrl = "meeting/update.json";
                    else
                        var sendUrl = "meeting/create.json";

                    console.log("SEND MEETING DATA");
                    console.log($scope.meeting);
                    $http.post(sendUrl, {newMeeting: oaUtility.jsonCast($scope.meeting)}, {proxy: true}).
                        success(function (data, status, headers, config) {
                            console.log('New identity: ' + data.__identity);
                            // this callback will be called asynchronously
                            // when the response is available
                            console.log("SUCCESS");
                            var modalOptions = {
                                headerText: 'Erfolg',
                                bodyText: 'Das Meeting wurde erfolgreich erstellt!'
                            };
                            var modalDefaults = {
                                templateUrl: '/template/modaldialog/success.html'
                            };
                            ModalDialog.showModal(modalDefaults, modalOptions);
                            $location.path("/meeting");


                        }).
                        error(function (data, status, headers, config) {
                            // called asynchronously if an error occurs
                            // or server returns response with an error status.
                            console.log("ERROR");
                            var modalOptions = {
                                headerText: 'Fehler',
                                bodyText: 'Es ist ein Fehler beim Übermitteln der Daten aufgetreten! Versuchen Sie es erneut!'
                            };
                            var modalDefaults = {
                                templateUrl: '/template/modaldialog/error.html'
                            };
                            ModalDialog.showModal(modalDefaults, modalOptions);

                        });
                }
                else {
                    var modalDefaults = {
                        templateUrl: '/template/modaldialog/error.html'
                    };
                    var modalOptions = {
                        headerText: 'Fehler',
                        bodyText: 'Es liegt ein Fehler bei den eingegebenen Daten vor! Es wurden keine Daten eingetragen!'
                    };
                    ModalDialog.showModal(modalDefaults, modalOptions);

                }
            };
            /*All Email Addresses for auto completion*/
            $scope.mailAdresses = [];

            $scope.updateMailAddresses = function (typed) {
                $scope.mailAdresses = [];

                // @todo Filtering stuff
                angular.forEach($scope.remoteUsers, function(remoteUser) {
                    $scope.mailAdresses.push(remoteUser.$mail);
                });
            }

            $scope.checkEntries = function () {
                var meetingEntries = false;
                var agendaItems = true;

                if (!$scope.meeting.title || $scope.meeting.title.length == 0)
                    return meetingEntries;
                if (!$scope.meeting.location || $scope.meeting.location.length == 0)
                    return meetingEntries;
                meetingEntries = true;

                angular.forEach($scope.meeting.agendaItems, function (agendaItem) {
                    if (agendaItem.title.length <= 0) {
                        agendaItems = false;
                        return;
                    }
                })
                if (agendaItems && meetingEntries)
                    return true;
                else
                    return false;
            };
            $scope.getUploader = function (idx) {
                return $scope.uploaders[idx];
            };

        }]);
