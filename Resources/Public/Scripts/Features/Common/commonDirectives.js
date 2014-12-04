/**
 * Created by Thomas on 04.12.14.
 */

angular.module("CommonDirectives", [])
    .directive("pointMe", function() {
        return {
            restrict : "AC",
            link : function(scope, element, attrs) {

                element.css("cursor", "pointer");

            }
        };
    });

