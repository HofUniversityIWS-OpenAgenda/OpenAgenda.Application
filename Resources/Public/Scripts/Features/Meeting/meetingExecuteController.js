/**
 * Created by Andi on 09.12.14.
 */

angular.module("Meeting")
    .controller('MeetingExecuteCtrl', ['$scope', '$rootScope', '$filter','$routeParams', '$resource', "breadcrumbs", "MeetingResourceHelper", "CommonHelperMethods",
    function ($scope, $rootScope, $filter, $routeParams, $resource, breadcrumbs, MeetingResourceHelper, CommonHelperMethods) {
        $scope.meetingId = $routeParams.meetingId;
        console.log($routeParams.meetingId);
        $scope.breadcrumbs = breadcrumbs;

        $scope.meeting = MeetingResourceHelper.getMeetingDetail($routeParams.meetingId).get(function (data) {
            console.log('Execute success, got data: ', data);
            console.log('datum', data.startDate);
            if (data.startDate)
            {
                data.startDate = CommonHelperMethods.getDateFromJSONString(data.startDate);
            }

            data.scheduledStartDate = CommonHelperMethods.getDateFromJSONString(data.scheduledStartDate);

            $scope.invitedUsers = [];
            // TODO: invitedUsers from Meeting
            for (var i = 0; i < $scope.meeting.invitations.length; i++) {

                $scope.invitedUsers.push({value: $scope.meeting.invitations[i].$participant.__identity,
                    text: $scope.meeting.invitations[i].$participant.name.firstName +' '+
                            $scope.meeting.invitations[i].$participant.name.lastName +' <'+
                            $scope.meeting.invitations[i].$participant.mail + '>'
                });

            };

            //$scope.invitedUsers = $scope.meeting.invitations;

        }, function (err) {
            alert('request failed');
        });

        $scope.task = {

        };

        // Neue Aufgabe
        //$scope.task;

        $scope.addTask = function(){
            // sendTask
            this.description = $scope.task.description;
            this.creationDate = new Date();
            this.scheduledDateTime = $scope.task.scheduledDateTime;
            this.user = $scope.task.user;
            this.title = $scope.task.title;

            //an server senden

            // task leeren;
            this.description = null;
            this.creationDate = null;
            this.scheduledDateTime = null;
            this.user = null;
            this.title = null;

        };

        $scope.getProtocolItem = function(sorting){
            var found = false;
            for (var i = 0; i < $scope.meeting.protocolItems.length; i++)
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


        $scope.showStatus = function() {
            var selected = $filter('filter')($scope.invitedUsers, {value: $scope.task.user});
            console.log('selected', $scope.task.user && selected.length, ($scope.task.user && selected.length != undefined) ? selected[0].value : 'undef');
            return ($scope.task.user && selected.length) ? selected[0].text : 'Verantwortlichen wählen';
        };

        $scope.startMeetng = function(){
            if ($scope.meeting.status < 2)
            {
                $scope.meeting.startDate = new Date();
                $scope.meeting.status = 2;
                console.log('meetingStart');
            }

        };
        $scope.endMeetng = function(){
            if ($scope.meeting.status < 3)
            {
                $scope.meeting.endDate = new Date();
                $scope.meeting.status = 3;
            }
        };
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
                    $scope.$parent.startMeetng();
                    modalInstance.dismiss();
                };
            };

            $scope.tooglePresent = function (index) {
                if($scope.meeting.invitations[index].role != "OpenAgenda.Application:MinuteTaker") {
                    if ($scope.meeting.invitations[index].status != 4)
                        $scope.meeting.invitations[index].status = 4;
                    else
                        $scope.meeting.invitations[index].status = 0;
                }
            };

            $scope.toogleMinuteTaker = function (index) {
                if($scope.meeting.invitations[index].role == "OpenAgenda.Application:MinuteTaker") {
                    $scope.meeting.invitations[index].role = "OpenAgenda.Application:Participant";
                    $scope.meeting.invitations[index].status = 0;
                }
                else {
                    $scope.meeting.invitations[index].role = "OpenAgenda.Application:MinuteTaker";
                    $scope.meeting.invitations[index].status = 4;
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