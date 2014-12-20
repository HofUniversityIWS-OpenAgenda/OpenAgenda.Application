/**
 * Created by Andi on 16.12.14.
 */

angular.module("Setting", [])
    .controller('userProfileCtrl', ['$scope', '$http', '$rootScope', '$routeParams', 'OpenAgenda.Data.Utility', 'ModalDialog', '$resource', "breadcrumbs",
        function($scope, $http, $rootScope, $routeParams, oaUtility, ModalDialog, $resource, breadcrumbs){
            $scope.breadcrumbs = breadcrumbs;
            $scope.profile = {};
            $scope.password = null;
            $scope.passwordRepeat = null;

            $http.get('setting/getProfile.json').success(function(profile) {
                $scope.profile = profile;
            });

            $scope.persist = function() {
                $http.post('setting/updateProfile.json', { person: oaUtility.jsonCast($scope.profile) })
                    .success(function() {
                        var modalOptions = {
                            headerText: 'Erfolg',
                            bodyText: 'Das Meeting wurde erfolgreich erstellt!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/success.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    })
                    .error(function() {
                        var modalOptions = {
                            headerText: 'Fehler',
                            bodyText: 'Es ist ein Fehler beim Übermitteln der Daten aufgetreten! Versuchen Sie es erneut!'
                        };
                        var modalDefaults = {
                            templateUrl: '/template/modaldialog/error.html'
                        };
                        ModalDialog.showModal(modalDefaults, modalOptions);
                    });
            };
}]);