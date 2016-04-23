(function () {
    'use strict';

    angular
        .module('app')
        .controller('SignInCtrl', SignInCtrl);

    SignInCtrl.$inject = ['$scope', 'AuthService'];

    /**
     * [SignInCtrl]
     * @param $scope
     * @constructor
     */
    function SignInCtrl ($scope, AuthService) {
        $scope.username   = '';
        $scope.password   = '';
        $scope.rememberme = true;

        $scope.signIn = function() {
            $scope.error   = '';
            $scope.success = '';
            $scope.details = '';

            AuthService.basicAuth($scope.username, $scope.password, function(error, response) {
                // if has been ocurred an error
                if (error) {
                    $scope.error = 'An error has been ocurred while sign in.';
                    $scope.details = JSON.stringify(response);
                } else {
                    $scope.username = '';
                    $scope.password = '';

                    // Add action here
                    $scope.success = 'User authorized sussessfuly.';
                    $scope.details = JSON.stringify(response);
                }
            });
        };
    }
})();