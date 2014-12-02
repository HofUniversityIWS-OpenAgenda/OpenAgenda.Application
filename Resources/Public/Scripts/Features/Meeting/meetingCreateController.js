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
        $scope.meeting;

        $scope.$watchCollection('meeting', function(newValue, oldValue) {
            console.log(newValue);
        });
    }])
    .directive("pointMe", function() {
        return {
            restrict : "AC",
            link : function(scope, element, attrs) {

                element.css("cursor", "pointer");

            }
        };
    });
