(function () {
    'use strict';

    angular
        .module('app')
        .controller('SignUpCtrl', SignUpCtrl);

    SignUpCtrl.$inject = ['$scope', 'UserService'];

    /**
     * [SignUpCtrl]
     * @param $scope
     * @param UserService
     * @constructor
     */
    function SignUpCtrl ($scope, UserService) {
        $scope.firstName       = '';
        $scope.lastName        = '';
        $scope.displayName     = '';
        $scope.email           = '';
        $scope.password        = '';
        $scope.passwordConfirm = '';
        $scope.agree           = false;

        $scope.onAgree = function () {
            $scope.agree = !$scope.agree;
        };

        $scope.signUp = function () {
            $scope.error   = '';
            $scope.success = '';
            $scope.details = '';

            if (!$scope.agree) {
                $scope.error = 'You have agree the Terms and Conditions.';
                return;
            }

            // Sign Up
            UserService.signUp($scope.firstName, $scope.lastName, $scope.displayName, $scope.email, $scope.password, $scope.agree, function(error, response) {
                if (error) {
                    $scope.error = 'An error has been ocurred while sign in.';
                    $scope.details = response;
                } else {
                    $scope.firstName       = '';
                    $scope.lastName        = '';
                    $scope.displayName     = '';
                    $scope.email           = '';
                    $scope.password        = '';
                    $scope.passwordConfirm = '';

                    // Add action here
                    $scope.success = 'User registered sussessfuly.';
                    $scope.details = response;
                }
            });
        }
    }
})();