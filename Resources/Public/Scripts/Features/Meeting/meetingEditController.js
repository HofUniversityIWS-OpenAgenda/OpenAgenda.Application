/**
 * Created by Thomas on 02.12.14.
 */
/**
 * This Module is used for editing and showing Meetings
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */
angular.module("Meeting")
    .controller('MeetingEditCtrl', ['$scope', '$filter', '$http', '$rootScope', '$location', '$routeParams', '$resource', "breadcrumbs", 'FileUploader', "MeetingResourceHelper", 'CommonHelperMethods', 'OpenAgenda.Data.Utility', 'ModalDialog',
        function ($scope, $filter, $http, $rootScope, $location, $routeParams, $resource, breadcrumbs, FileUploader, MeetingResourceHelper, CommonHelperMethods, oaUtility, ModalDialog) {
            $scope.breadcrumbs = breadcrumbs;
            $scope.loading = true;

            console.log("Meeting Edit Controller loaded");

            $scope.mailAddresses = [];
            $scope.meetingId = $routeParams.meetingId;
            $scope.uploaders = [];
            $scope.remoteUsers = [];

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

            $http.get('person/index.json').success(function (persons) {
                $scope.remoteUsers = persons;
                angular.forEach($scope.remoteUsers, function (remoteUser) {
                    $scope.mailAddresses.push(remoteUser.$mail);
                });
            });

            if (typeof $scope.meetingId === "undefined") {
                $scope.editMode = true;
                $scope.loading = false;
            }

            if (typeof $scope.meetingId != "undefined") {
                $scope.meeting = MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    data.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(data.scheduledStartDate);
                    for (var i = 0; i <= $scope.meeting.agendaItems.length; i++) {
                        if (typeof $scope.uploaders[i] === "undefined") {
                            $scope.uploaders.push(new FileUploader());
                        }
                    }
                    $scope.loading = false;
                    if ($scope.meeting.status >= 1) {
                        $scope.editMode = false;

                    } else {
                        $scope.editMode = $scope.meeting.$permissions.edit;
                    }

                }, function (err) {
                    alert('request failed');
                });

            } else if ($scope.status == 3) {
                $scope.editMode = false;
            }

            if (typeof $scope.meeting === "undefined") {
                $scope.meeting = new Meeting();
            }

            $scope.addNewAgendaItem = function () {
                $scope.meeting.agendaItems.push(new AgendaItem($scope.meeting.agendaItems.length + 1));
            };

            $scope.removeAgendaItem = function (idx) {
                $scope.meeting.agendaItems.splice(idx, 1);
                $scope.uploaders.splice(idx, 1);

                for (var i = $scope.meeting.agendaItems.length - 1; i >= idx; i--) {
                    $scope.meeting.agendaItems[i].sorting -= 1;
                }
            };

            $scope.addNewInvitation = function (mail) {
                var single_User = $filter('filter')($scope.remoteUsers, function (person) {
                    return person.$mail === mail;
                })[0];

                $scope.contains = false;
                angular.forEach($scope.meeting.invitations, function (invitation) {
                    if (invitation.participant == single_User.__identity)
                        $scope.contains = true;
                });
                if (!$scope.contains)
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

                    if (typeof $scope.meetingId != "undefined")
                        var sendUrl = "meeting/update.json";
                    else
                        var sendUrl = "meeting/create.json";

                    $http.post(sendUrl, {meeting: oaUtility.jsonCast($scope.meeting)}, {proxy: true}).
                        success(function (data, status, headers, config) {
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

            /*All Email Addresses for auto completion
             * In next Version search for specific users
             * */
            $scope.updateMailAddresses = function (typed) {

            };

            $scope.checkEntries = function () {
                var meetingEntries = false;
                var agendaItems = true;

                if (!$scope.meeting.title || $scope.meeting.title.length == 0)
                    return meetingEntries;
                if (!$scope.meeting.location || $scope.meeting.location.length == 0)
                    return meetingEntries;
                meetingEntries = true;

                angular.forEach($scope.meeting.agendaItems, function (agendaItem) {
                    if (!agendaItem.title ||agendaItem.title.length == 0) {
                        agendaItems = false;
                        return;
                    }
                    if (!agendaItem.description ||agendaItem.title.description == 0) {
                        agendaItems = false;
                        return;
                    }
                });
                if (agendaItems && meetingEntries)
                    return true;
                else
                    return false;
            };

            $scope.getUploader = function (idx) {
                return $scope.uploaders[idx];
            };

            $scope.reloadTasks = function () {
                MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    $scope.meeting.tasks = data.tasks;
                })
            };

        }]);
