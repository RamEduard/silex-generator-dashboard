(function () {
    'use strict';

    angular
        .module('app')
        .factory('UserService', UserService);

    function UserService ($http, API_URL) {
        var service = {};

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

        service.signIn = function(email, password, callback) {
            // MD5 CryptoJS
            // var password = CryptoJS.MD5(password).toString();

            return $http({
                method: 'POST',
                url: API_URL + '/auth/form',
                data: {
                    email: email,
                    password: password
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

        service.signUp = function (firstName, lastName, displayName, email, password, agree, callback) {
            // MD5 CryptoJS
            // var password = CryptoJS.MD5(password).toString();
            
            return $http({
                method: 'POST',
                url: API_URL + '/sign-up',
                data: {
                    firstName: firstName,
                    lastName: lastName,
                    displayName: displayName,
                    email: email,
                    password: password, 
                    agree: agree
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

        service.contact = function (name, subject, email, message, callback) {
            return $http({
                method: 'POST',
                url: API_URL + '/contact',
                data: {
                    name: name,
                    subject: subject,
                    email: email,
                    message: message
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