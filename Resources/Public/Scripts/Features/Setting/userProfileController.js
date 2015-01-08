/**
 * @memberOf angular_module
 *
 * @author Oliver Hader <oliver@typo3.org>
 *
 * @description User Profile controller
 */
angular.module("Setting", [])

.controller('userProfileCtrl', ['$scope', '$http', 'OpenAgenda.Data.Utility', 'ModalDialog', 'breadcrumbs',
        /**
         * @class angular_module.Setting.UserProfileCtrl
         * @param $scope {object}
         * @param $http {object}
         * @param oaUtility {object}
         * @param ModalDialog {object}
         * @param breadcrumbs {object}
         */
        function($scope, $http, oaUtility, ModalDialog, breadcrumbs){
        $scope.breadcrumbs = breadcrumbs;
        $scope.profile = {};
        // @todo Password change is missing
        $scope.password = null;
        $scope.passwordRepeat = null;
        $scope.canModifyPassword = false;

        $http.get('setting/getProfile.json').success(function(profile) {
            $scope.profile = profile;
            $scope.canModifyPassword = ($scope.profile.$currentProvider === 'DefaultProvider');
        });

        $scope.persist = function() {
            var data = {
                person: oaUtility.jsonCast($scope.profile),
                password: $scope.canModifyPassword
                    ? { password: $scope.password, passwordRepeat: $scope.passwordRepeat }
                    : null
            };
            $http.post('setting/updateProfile.json',  data)
                .success(function() {
                    var modalOptions = {
                        headerText: 'Erfolg',
                        bodyText: 'Ihr Profil wurde aktualisiert...'
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