/**
 * User Profile controller
 * @author Oliver Hader <oliver@typo3.org>
 */
angular.module("Setting", [])
.controller('userProfileCtrl', ['$scope', '$http', 'OpenAgenda.Data.Utility', 'ModalDialog', 'breadcrumbs',
    function($scope, $http, oaUtility, ModalDialog, breadcrumbs){
        $scope.breadcrumbs = breadcrumbs;
        $scope.profile = {};
        // @todo Password change is missing
        $scope.password = null;
        $scope.passwordRepeat = null;

        $http.get('setting/getProfile.json').success(function(profile) {
            $scope.profile = profile;
        });

        $scope.canModifyPassword = function() {
            return $scope.profile.$currentProvider === 'DefaultProvider';
        };

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