(function () {
    'use strict';

    angular
        .module('app')
        .controller('ContactCtrl', ContactCtrl);

    ContactCtrl.$inject = ['$scope', 'UserService'];

    function ContactCtrl ($scope, UserService) {
        $scope.name    = '';
        $scope.subject = '';
        $scope.email   = '';
        $scope.message = '';

        $scope.contact = function() {
            $scope.error   = '';
            $scope.success = '';
            $scope.details = '';

            UserService.contact($scope.name, $scope.subject, $scope.email, $scope.message, function (error, response) {
                if (error) {
                    $scope.error = error.message;
                    $scope.details = response;
                } else {
                    // Reset data
                    $scope.name    = '';
                    $scope.subject = '';
                    $scope.email   = '';
                    $scope.message = '';

                    // Success message
                    $scope.success = 'Message sent successfuly';
                    $scope.details = response;
                }
            });
        };
    }
})();