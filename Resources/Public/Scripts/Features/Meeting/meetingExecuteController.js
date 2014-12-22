/**
 * Created by Andi on 09.12.14.
 */

angular.module("Meeting")
    .controller('MeetingExecuteCtrl', ['$scope', '$rootScope', '$interval', '$location', '$http', '$filter', '$routeParams', '$resource', "breadcrumbs", "MeetingResourceHelper", "OpenAgenda.Data.Utility", "CommonHelperMethods", "ModalDialog",
        function ($scope, $rootScope, $interval, $location, $http, $filter, $routeParams, $resource, breadcrumbs, MeetingResourceHelper, oaUtility, CommonHelperMethods, ModalDialog) {
            $scope.meetingId = $routeParams.meetingId;
            console.log($routeParams.meetingId);
            $scope.breadcrumbs = breadcrumbs;
            $scope.meeting = [];
            var getnewMeeting;
            function reloadMeetingData() {
                MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    if (data.startDate) {
                        data.startDate = CommonHelperMethods.getDateFromJSONString(data.startDate);
                    }
                    data.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(data.scheduledStartDate);
                    $scope.meeting = data;
                    if(!$scope.meeting.$permissions.minutes && !getnewMeeting) {
                        getnewMeeting = $interval(function () {
                            if($scope.meeting.status == 2)
                                reloadMeetingData();
                        }, 10000);
                    }
                }, function (err) {
                });

            };

            reloadMeetingData();

            $scope.meeting.tasks = {};
            //$scope.task = {};

            // Neue Aufgabe

            $scope.addTask = function () {
                $scope.meeting.tasks.push(new TaskItem($scope.meeting.tasks.length));
            };

            $scope.sendProtocollItem = function (id) {
                sendMeetingData(oaUtility.jsonCast($scope.meeting), 'Beim Übertragen der Daten ist ein Fehler aufgetreten!');
            };
            $scope.sendTaskItem = function (idx) {
                var x = oaUtility.jsonCast($scope.meeting);
                console.log("X", x);
                if ($scope.meeting.tasks[idx].title && $scope.meeting.tasks[idx].dueDate && $scope.meeting.tasks[idx].assignee && $scope.meeting.tasks[idx].description)
                    sendMeetingData(x, 'Beim Übertragen der Daten ist ein Fehler aufgetreten!');

            };

            function sendMeetingData(meeting, bodyText) {
                $http.post('meeting/update.json', {meeting: meeting}, {proxy: true}).
                    success(function (data, status, headers, config) {
                        console.log("SUCCESS");
                        reloadMeetingData();

                    }).error(function (data, status, headers, config) {
                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: bodyText
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    });
            }

            $scope.getProtocolItem = function (sorting) {
                var found = false;
                for (var i = 0; i < $scope.meeting.protocolItems.length; i++) {
                    if ($scope.meeting.protocolItems[i].sorting == sorting) {
                        found = true;
                        return $scope.meeting.protocolItems[i];
                    }
                }
                if (!found) {
                    //add new Item
                    var newProtocolItem = function ProtocollItem(sorting) {
                        this.__identity;
                        this.description = null;
                        this.creationDate = new Date();
                        this.modificationDate;
                        this.sorting = sorting;
                    }
                    $scope.meeting.protocolItems.push(newProtocolItem);
                    return newProtocolItem;
                }
            };

            function TaskItem(count) {
                this.status = 0;
            }

            $scope.showStatus = function (index) {
                var x = "Verantwortlichen wählen";

                //If task has already a assignee
                //Should task.$assignee be deleted, if a new assignee is choosen?
                //ATM task.assignee is the ID of the new assignee
                if ($scope.meeting.tasks[index].assignee) {
                    var selected = $filter('filter')($scope.meeting.invitations, {participant: $scope.meeting.tasks[index].assignee});
                    if (selected.length)
                        return selected[0].$participant.$mail;
                    else
                        return x;
                }
                else
                    return x;
            };
            $scope.removeTasks = function (idx) {
                console.log({
                    task: $scope.meeting.tasks[idx].__identity,
                    meeting: $scope.meeting.__identity
                });
                $http.post('task/' + $scope.meeting.tasks[idx].__identity + '/delete.json', {
                    task: $scope.meeting.tasks[idx].__identity,
                    meeting: $scope.meeting.__identity
                }, {proxy: true}).
                    success(function (data, status, headers, config) {
                        console.log("SUCCESS");
                        reloadMeetingData();
                    }).error(function (data, status, headers, config) {
                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Beim Starten des Meetings ist ein Fehler aufgetreten!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    });
            };


            $scope.startMeeting = function () {

                angular.forEach($scope.meeting.invitations, function (invitation) {
                    delete invitation.role;
                });

                var meeting = oaUtility.jsonCast($scope.meeting);
                angular.forEach(meeting.agendaItems, function(agendaItem) {
                    if (typeof agendaItem.note !== 'undefined') {
                        delete agendaItem.note;
                    }
                });

                // sendMeetingData(x, 'Beim Starten des Meetings ist ein Fehler aufgetreten!');
                $http.post('meeting/start.json', { meeting: meeting }, { proxy: true }).
                    success(function (data, status, headers, config) {
                        console.log("SUCCESS");
                        reloadMeetingData();
                    }).error(function (data, status, headers, config) {
                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Beim Starten des Meetings ist ein Fehler aufgetreten!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    });
            };
            $scope.oncheck = function () {
                sendMeetingData(oaUtility.jsonCast($scope.meeting), 'Beim Speichern des Meetings ist ein Fehler aufgetreten!');

            };
            $scope.endMeeting = function () {
                console.log('endMeeting', $scope.meeting);
                var tasksCorrect = false;

                for (var i = 0; i < $scope.meeting.tasks.length; i++ ) {
                    if ($scope.meeting.tasks[i].title && $scope.meeting.tasks[i].dueDate && $scope.meeting.tasks[i].assignee && $scope.meeting.tasks[i].description) {
                        tasksCorrect = true;
                    } else {
                        tasksCorrect = false;
                        break;
                    }
                }
                console.log(tasksCorrect);

                if (tasksCorrect){
                    $http.post('meeting/close.json', {meeting: oaUtility.jsonCast($scope.meeting)}, {proxy: true}).
                        success(function (data, status, headers, config) {
                            console.log("SUCCESS");
                            var modalOptions = {
                                headerText: 'Erfolg',
                                bodyText: 'Das Meetings wurde erfolgreich beendet!'
                            };
                            var modalDefaults = {
                                templateUrl: '/template/modaldialog/success.html'
                            };
                            $location.path("/");
                            ModalDialog.showModal(modalDefaults, modalOptions);
                        }).error(function (data, status, headers, config) {
                            var modalOptions = {
                                headerText: 'Fehler',
                                bodyText: 'Beim Beenden des Meetings ist ein Fehler aufgetreten!'
                            };
                            var modalDefaults = {
                                templateUrl: '/template/modaldialog/error.html'
                            };
                            ModalDialog.showModal(modalDefaults, modalOptions);
                        });
                } else{
                    var modalOptions = {
                        headerText: 'Fehler',
                        bodyText: 'Beim Beenden des Meetings ist ein Fehler aufgetreten! Nicht alle Aufgaben sind korrekt gefüllt! Überprüfen Sie bitte Ihre Aufgaben!'
                    };
                    var modalDefaults = {
                        templateUrl: '/template/modaldialog/error.html'
                    };
                    ModalDialog.showModal(modalDefaults, modalOptions);
                }

            };

            $scope.$on('$destroy', function () {
                if(getnewMeeting)
                    $interval.cancel(getnewMeeting);
            });
    }])

/**This Controller handles the meeting start scenario
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */
    .controller('MeetingExecuteModalCtrl', ['$scope', '$rootScope', '$http', "CommonHelperMethods", '$modal', '$log',
        function ($scope, $rootScope, $http, CommonHelperMethods, $modal, $log) {

            $scope.open = function (size) {
                var modalInstance = $modal.open({
                    templateUrl: '/template/meeting/executemodal.html',
                    controller: 'MeetingExecuteModalInstanceCtrl',
                    size: size,
                    resolve: {
                        meeting: function () {
                            return $scope.meeting;
                        }
                    }
                });

                modalInstance.close = function () {
                    if ($scope.meeting.minuteTaker) {
                        $scope.$parent.startMeeting();
                        modalInstance.dismiss();
                    }
                };
            };

            $scope.tooglePresent = function (index) {
                if ($scope.meeting.invitations[index].role != "OpenAgenda.Application:MinuteTaker") {
                    if ($scope.meeting.invitations[index].available != true) {
                        $scope.meeting.invitations[index].available = true;
                    }
                    else
                        $scope.meeting.invitations[index].available = false;
                }
            };

            $scope.toogleMinuteTaker = function (index) {
                if ($scope.meeting.invitations[index].role == "OpenAgenda.Application:MinuteTaker") {
                    $scope.meeting.invitations[index].role = "OpenAgenda.Application:Participant";
                    $scope.meeting.invitations[index].available = true;
                    $scope.meeting.minuteTaker = null;
                }
                else if (!$scope.meeting.minuteTaker) {
                    $scope.meeting.invitations[index].role = "OpenAgenda.Application:MinuteTaker";
                    $scope.meeting.invitations[index].available = true;
                    $scope.meeting.minuteTaker = $scope.meeting.invitations[index].$participant.__identity;
                }
            };
        }])
/**This controller is used to handle the modal view to view and change a tasks state
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */
    .controller('MeetingExecuteModalInstanceCtrl', function ($scope, $modalInstance, CommonHelperMethods, meeting) {

        $scope.meeting = meeting;
        $scope.ok = function () {
            $modalInstance.close("OK");
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('DISMISS');
        };

    });