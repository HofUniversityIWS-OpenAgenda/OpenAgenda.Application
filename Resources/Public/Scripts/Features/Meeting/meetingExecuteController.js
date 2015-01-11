/**
 * @memberOf angular_module
 *
 * @author Andreas Weber <andreas.weber@hof-university.de>
 */

angular.module("Meeting")
/**
 * @class angular_module.Meeting.MeetingExecuteCtrl
 */
    .controller('MeetingExecuteCtrl', ['$scope', '$rootScope', '$interval', '$location', '$http', '$filter', '$routeParams', '$resource', "breadcrumbs", "MeetingResourceHelper", "OpenAgenda.Data.Utility", "CommonHelperMethods", "ModalDialog",
        function ($scope, $rootScope, $interval, $location, $http, $filter, $routeParams, $resource, breadcrumbs, MeetingResourceHelper, oaUtility, CommonHelperMethods, ModalDialog) {
            /**@memberOf angular_module.Meeting.MeetingExecuteCtrl */
            $scope.meetingId = $routeParams.meetingId;
            /**@memberOf angular_module.Meeting.MeetingExecuteCtrl */
            $scope.breadcrumbs = breadcrumbs;

            console.log("Meeting Execute Controller loaded");
            /**@memberOf angular_module.Meeting.MeetingExecuteCtrl */
            $scope.meeting = [];
            /**@memberOf angular_module.Meeting.MeetingExecuteCtrl */
            var getnewMeeting;
            /**
             *
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @description Fetches the Data from Server and replaces the local Data. Its used to synchronize the Data between the meeting attendees.
             */
            function reloadMeetingData() {
                MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
                    if (data.startDate) {
                        data.startDate = CommonHelperMethods.getDateFromJSONString(data.startDate);
                    }
                    data.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(data.scheduledStartDate);
                    $scope.meeting = data;

                    if(!getnewMeeting) {
                        getnewMeeting = $interval(function () {

                            if($scope.meeting.status == 2 && !$scope.meeting.$permissions.minutes)
                                reloadMeetingData();
                        }, 10000);
                    }
                }, function (err) {
                });

            };

            reloadMeetingData();

            $scope.meeting.tasks = {};

            // Neue Aufgabe

            /**
             * @function
             * @description Adds a task to the Array. Creates automatically a new Taskitem
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             */
            $scope.addTask = function () {
                $scope.meeting.tasks.push(new TaskItem($scope.meeting.tasks.length));
            };
            /**
             * @function
             * @description Sends the changed ProtocollItem to the server
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             */
            $scope.sendProtocollItem = function (id) {
                sendMeetingData(oaUtility.jsonCast($scope.meeting), 'Beim Übertragen der Daten ist ein Fehler aufgetreten!');
            };
            /**
             * @function
             * @description Sends the changed TaskItem to the server
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             */
            $scope.sendTaskItem = function (idx) {
                var x = oaUtility.jsonCast($scope.meeting);
                console.log("X", x);
                if ($scope.meeting.tasks[idx].title && $scope.meeting.tasks[idx].dueDate && $scope.meeting.tasks[idx].assignee && $scope.meeting.tasks[idx].description)
                    sendMeetingData(x, 'Beim Übertragen der Daten ist ein Fehler aufgetreten!');

            };
            /**
             *
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @param {object} meeting The Meeting to send
             * @param {string} bodyText The Message to display on error.
             * @description Sends the Meetingdata to the server. Displays a modal Dialog if something went wrong.
             */
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
            /**
             *
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @param {int} sorting The number of the AgendaItem
             * @description Returns Protocolitem for a AgendaPoint. If the AgendaPoint has no Protocolitem a new one is created.
             * @returns {object} ProtcolItem
             */
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

            /**
             *
             * @constructor
             * @param count {int} Not used
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             */
            function TaskItem(count) {
                this.status = 0;
            }
            /**
             *
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @param {int} index
             * @description Used to get the  mail address for the assingees
             * @returns mailaddress {string}
             */
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
            /**
             *
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @param {int} idx
             * @description Removes a Tasks an send an delete event to the server
             */
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

            /**
             *
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @description Starts the meeting and prepares the meeting object for the meeting.
             */
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
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteCtrl
             * @description End the meeting sends a close event to the server
             */
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

/**
 *
 * @class angular_module.Meeting.MeetingExecuteModalCtrl
 * @description This Controller handles the meeting start scenario.
 * Initial handling to open and close the modal view implemented by Thomas Winkler.
 * The concrete function to start the meeting implemented by Andreas Weber.
 *
 * @author Andreas Weber <andreas.weber@hof-university.de>
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */
    .controller('MeetingExecuteModalCtrl', ['$scope', '$rootScope', '$http', "CommonHelperMethods", '$modal', '$log',
        function ($scope, $rootScope, $http, CommonHelperMethods, $modal, $log) {
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteModalCtrl
             * @param size {string} Size of the dialog
             * @description Opens the modal dialog. Passes the current meeting to the modal view.
             */
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
                /**
                 * @function
                 * @memberOf angular_module.Meeting.MeetingExecuteModalCtrl
                 * @description Is triggered when the dialog is closed. Starts the meeting.
                 */
                modalInstance.close = function () {
                    if ($scope.meeting.minuteTaker) {
                        $scope.$parent.startMeeting();
                        modalInstance.dismiss();
                    }
                };
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteModalCtrl
             * @description Toggles the selected table row. Sets the present assignees.
             * @param index {int} Index of tapped selection
             */
            $scope.tooglePresent = function (index) {
                if ($scope.meeting.invitations[index].role != "OpenAgenda.Application:MinuteTaker") {
                    if ($scope.meeting.invitations[index].available != true) {
                        $scope.meeting.invitations[index].available = true;
                    }
                    else
                        $scope.meeting.invitations[index].available = false;
                }
            };
            /**
             * @function
             * @memberOf angular_module.Meeting.MeetingExecuteModalCtrl
             * @description Toggles if someone is the minutetaker. Only one can be the minutetaker!
             * @param index {int} Index of tapped selection
             */
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
/**
 * @class angular_module.Meeting.MeetingExecuteModalInstanceCtrl
 * @description This controller is used to handle the modal view to view and change a tasks state
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */

    .controller('MeetingExecuteModalInstanceCtrl', function ($scope, $modalInstance, CommonHelperMethods, meeting) {

        $scope.meeting = meeting;
        /**
         * @function
         * @memberOf angular_module.Meeting.MeetingExecuteModalInstanceCtrl
         * @description Sends the string "OK" to the MeetingExecuteModalCtrl. Is fired is someone presses the OK button
         */
        $scope.ok = function () {
            $modalInstance.close("OK");
        };
        /**
         * @function
         * @memberOf angular_module.Meeting.MeetingExecuteModalInstanceCtrl
         * @description Sends the string "DISMISS" to the MeetingExecuteModalCtrl. Is fired is someone presses the Cancel button
         */
        $scope.cancel = function () {
            $modalInstance.dismiss('DISMISS');
        };

    });