/**
 * This Module defines Directives for changing the HTML Output
 *
 * Use it as HTML-Tags
 * Examples:
 * <td task-status="true"></td>
 *
 * Note: Specify a value the attributes to fulfill the XHTML Standard
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
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

