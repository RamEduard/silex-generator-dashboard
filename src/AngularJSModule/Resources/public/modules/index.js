(function () {
	'use strict';

	angular.module('app.index', ['ngRoute'])

	.config(['$routeProvider', function($routeProvider) {
	  $routeProvider.when('/index', {
	    templateUrl: TEMPLATES_PATH + '/index.html',
	    controller: 'IndexCtrl'
	  });
	}])

	.controller('IndexCtrl', [function() {

	}]);
})();