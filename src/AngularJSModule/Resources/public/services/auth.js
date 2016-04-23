(function () {
    'use strict';

    angular
        .module('app')
        .factory('AuthService', AuthService);

    function AuthService ($http, API_URL) {
        var service = {};

        function _transformErrors(errorResp) {
            
        }

        service.basicAuth = function(email, password, callback) {
            // MD5 CryptoJS
            // var password = CryptoJS.MD5(password).toString();

            // Basic auth btoa
            var hash = btoa(email + ':' + password);

            return $http({
                method: 'POST',
                url: API_URL + '/auth/basic',
                headers: {
                    'Authorization': 'Basic ' + hash
                }
            })
                .then(function (response) {
                    if (response.status == 200) {
                        callback(null, response['data']);
                    }
                })
                .catch(function (errorResp) {
                    callback(new Error(errorResp['data']['error']), errorResp['data']);
                });
        };

        return service;
    }
})();
