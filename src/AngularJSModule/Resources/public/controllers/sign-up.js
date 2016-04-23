(function () {
    'use strict';

    angular
        .module('app')
        .controller('SignUpCtrl', SignUpCtrl);

    SignUpCtrl.$inject = ['$scope', 'AuthService'];

    /**
     * [SignUpCtrl]
     * @param $scope
     * @constructor
     */
    function SignUpCtrl ($scope, AuthService) {
        $scope.username   = '';
        $scope.password   = '';
        $scope.rememberme = true;

        
    }
})();