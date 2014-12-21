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


           /* for (var i = 0; i < $scope.meeting.tasks.length; i++) {

                $scope.invitedUsers.push({value: $scope.meeting.invitations[i].$participant.__identity,
                    text: $scope.meeting.invitations[i].$participant.name.firstName +' '+
                    $scope.meeting.invitations[i].$participant.name.lastName +' <'+
                    $scope.meeting.invitations[i].$participant.$mail + '>'
                });

            };*/

            //$scope.invitedUsers = $scope.meeting.invitations;

        }, function (err) {
            alert('request failed');
        });

         $scope.meeting.tasks = {};
        //$scope.task = {};

        // Neue Aufgabe

        $scope.addTask = function(){
            $scope.meeting.tasks.push(new TaskItem($scope.meeting.tasks.length));

            // sendTask
            /*this.description = $scope.task.description;
            this.creationDate = new Date();
            this.scheduledDateTime = $scope.task.scheduledDateTime;
            var user = $filter('filter')($scope.invitedUsers, {value: $scope.task.user});
            this.user = user[0].value;
            this.title = $scope.task.title;

            $scope.task.description = $scope.task.description;
            $scope.task.creationDate = new Date();
            $scope.task.dueDate = $scope.task.dueDate;
            var user = $filter('filter')($scope.invitedUsers, {value: $scope.task.user});
            $scope.task.user = user[0].value;
            $scope.task.title = $scope.task.title;


            //an server senden

            // task leeren;
            this.description = null;
            this.creationDate = null;
            this.dueDate = null;
            this.user = null;
            this.title = null;

            $scope.task = {};

            $scope.tasks.push($scope.task);

            //$scope.createNewTask = true;
            */
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

        function TaskItem(count){
            this.meetingId = $scope.meeting.__identity;
            this.TaskNr = count;
        }

        $scope.showStatus = function(index) {
            var x = "Verantwortlichen wählen";

            //If task has already a assignee
            //Should task.$assignee be deleted, if a new assignee is choosen?
            //ATM task.assignee is the ID of the new assignee
            if($scope.meeting.tasks[index].assignee) {
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
          $scope.meeting.tasks.splice( idx, 1 );
        };


        $scope.startMeeting = function(){
            if ($scope.meeting.status < 2)
            {
                $scope.meeting.startDate = new Date();
                $scope.meeting.status = 2;
                console.log('meetingStart');
            }

        };
        $scope.endMeeting = function(){
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
                    $scope.$parent.startMeeting();
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