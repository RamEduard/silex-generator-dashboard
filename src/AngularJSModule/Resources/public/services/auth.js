(function () {
    'use strict';

    angular
        .module('app')
        .factory('AuthService', AuthService);

    function AuthService ($http) {
        var service = {};

        return service;
    }
})();
