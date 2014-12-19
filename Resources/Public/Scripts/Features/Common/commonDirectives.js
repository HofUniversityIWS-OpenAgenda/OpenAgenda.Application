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
    .directive('taskStatus', function () {

        return {
            template: '<span ng-switch="task.status"> ' +
            '<span ng-switch-when="0">Laufend</span> ' +
            '<span ng-switch-when="1">Abgeschlossen</span> ' +
            '<span ng-switch-when="2">Abgebrochen</span> ' +
            '<span ng-switch-default>Unbekannt</span> ' +
            '</span>'
        };
    })
    .directive('meetingStatus', function () {

        return {
            template: '<span ng-switch="meeting.status"> ' +
            '<span ng-switch-when="0">Vorgeplant</span> ' +
            '<span ng-switch-when="1">Geplant</span> ' +
            '<span ng-switch-when="2">Läuft</span> ' +
            '<span ng-switch-when="3">Abgeschlossen</span> ' +
            '<span ng-switch-default>Unbekannt</span> ' +
            '</span>'
        };
    })
    .directive("pointMe", function () {
        return {
            restrict: "AC",
            link: function (scope, element, attrs) {

                element.css("cursor", "pointer");

            }
        };
    })
    .directive('timedClick', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                var fn = $parse(attr['timedClick']);
                var delay = 200, clicks = 0, timer = null;
                element.on('click', function (event) {
                    clicks++;  //count clicks
                    if (clicks === 1) {
                        timer = setTimeout(function () {
                            scope.$apply(function () {
                                fn(scope, {$event: event});
                            });
                            clicks = 0;
                        }, delay);
                    } else {
                        clearTimeout(timer);
                        clicks = 0;
                    }
                });
            }
        };
    }]);

