/**
 * @module CommonServices
 *
 * @description This Module defines custom Services that are used in the whole application
 *
 * @author Thomas Winkler <thomas.winkler@hof-university.de>
 */
angular.module("CommonServices", [])
    /**
     * @description This service provides a generic modal Dialog.
     * It should be used to show a simple dialog without interaction.
     * The dialog can be personalized with modalOptions and modalDefaults
     *
     * @author Thomas Winkler <thomas.winkler@hof-university.de>
     * @function service
     * @param {string} "ModalDialog" Identifier
     * @param {string} "$modal" Injection
     * @param {function} function($modal)
     *
     * @example var modalOptions = {
     *      headerText: 'Erfolg',
            bodyText: 'Das Meetings wurde erfolgreich beendet!'
            };
     var modalDefaults = {
            templateUrl: '/template/modaldialog/success.html'
            };
     ModalDialog.showModal(modalDefaults, modalOptions);
     */
    .service('ModalDialog', ['$modal',
        function ($modal) {

            var modalDefaults = {
                backdrop: true,
                keyboard: true,
                modalFade: true,
                templateUrl: '/template/modaldialog/index.html'

            };

            var modalOptions = {
                closeButtonText: 'CLOSE',
                actionButtonText: 'OK',
                headerText: 'HEADER',
                bodyText: 'BODY'
            };

            this.showModal = function (customModalDefaults, customModalOptions) {
                if (!customModalDefaults) customModalDefaults = {};
                customModalDefaults.backdrop = 'static';
                return this.show(customModalDefaults, customModalOptions);
            };

            this.show = function (customModalDefaults, customModalOptions) {
                var tempModalDefaults = {};
                var tempModalOptions = {};

                angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);

                angular.extend(tempModalOptions, modalOptions, customModalOptions);

                if (!tempModalDefaults.controller) {
                    tempModalDefaults.controller = function ($scope, $modalInstance) {
                        $scope.modalOptions = tempModalOptions;
                        $scope.modalOptions.ok = function (result) {
                            $modalInstance.close(result);
                        };
                        $scope.modalOptions.close = function (result) {
                            $modalInstance.dismiss('cancel');
                        };
                    }
                }

                return $modal.open(tempModalDefaults).result;
            };

        }]);

