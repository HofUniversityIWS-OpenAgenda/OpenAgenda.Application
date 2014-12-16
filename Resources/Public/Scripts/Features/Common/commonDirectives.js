/**
 * Created by Thomas on 04.12.14.
 */

angular.module("CommonDirectives", [])
    .directive('taskStatus', function() {

        return {
            template:   '<span ng-switch="task.status"> ' +
            '<span ng-switch-when="0">Laufend</span> ' +
            '<span ng-switch-when="1">Abgeschlossen</span> ' +
            '<span ng-switch-when="2">Abgebrochen</span> ' +
            '<span ng-switch-default>Unbekannt</span> ' +
            '</span>'
        };
    })
    .directive('meetingStatus', function() {

        return {
            template:   '<span ng-switch="meeting.status"> ' +
            '<span ng-switch-when="0">Vorgeplant</span> ' +
            '<span ng-switch-when="1">Geplant</span> ' +
            '<span ng-switch-when="2">Läuft</span> ' +
            '<span ng-switch-when="3">Abgeschlossen</span> ' +
            '<span ng-switch-default>Unbekannt</span> ' +
            '</span>'
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

